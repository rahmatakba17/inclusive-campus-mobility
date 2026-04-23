<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BusController as AdminBusController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\BusPollingController;

// ===== LIGHTWEIGHT POLLING API (NO WEB MIDDLEWARE — Ultra-Fast) =====
// Route ini melewati Session, CSRF, Cookie middleware agar polling ringan.
Route::withoutMiddleware(['web'])->middleware([])->group(function () {
    Route::get('/api/poll/buses', [BusPollingController::class, 'index'])->name('api.poll.buses');
});

// ===== LANDING PAGE =====
Route::get('/', function () {
    $buses = \App\Models\Bus::where('status', 'active')->orderBy('bus_number')->get();
    $terminals = \App\Models\Terminal::orderBy('order')->get();
    return view('welcome', compact('buses', 'terminals'));
})->name('home');

// ===== MAP PAGE (PUBLIC) =====
Route::get('/map', function () {
    $terminals = \App\Models\Terminal::orderBy('order')->get();
    return view('map', compact('terminals'));
})->name('map');

// ===== GUIDE PAGE (PUBLIC) =====
Route::get('/guide', function () {
    return view('guest.guide');
})->name('guide');

// ===== SIMULATION API (PUBLIC, NO AUTH) =====
Route::prefix('api/simulation')->name('api.simulation.')->group(function () {
    Route::get('/buses', [SimulationController::class, 'buses'])->name('buses');
    Route::get('/bus/{bus}/seats', [SimulationController::class, 'seats'])->name('seats');
    Route::post('/bus/{bus}/auto-finish', [SimulationController::class, 'autoFinish'])->name('auto-finish');
    Route::get('/terminals', [SimulationController::class, 'terminals'])->name('terminals');
    Route::get('/status', [SimulationController::class, 'status'])->name('status');
    Route::get('/stats', [SimulationController::class, 'liveStats'])->name('stats');
});

// Admin Tips API (public read-only, anonymized)
Route::get('/api/admin/tips', [SimulationController::class, 'adminTips'])->name('api.admin.tips');

// ===== GUEST BOOKING ROUTES (NO LOGIN) =====
Route::prefix('guest')->name('guest.')->middleware('throttle:30,1')->group(function () {
    Route::get('/buses', [\App\Http\Controllers\GuestBookingController::class, 'buses'])->name('buses');
    Route::get('/booking/{bus}', [\App\Http\Controllers\GuestBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [\App\Http\Controllers\GuestBookingController::class, 'store'])->middleware('throttle:5,1')->name('booking.store');
    Route::get('/booking/success/{code}', [\App\Http\Controllers\GuestBookingController::class, 'success'])->name('booking.success');
});

// ===== AUTH ROUTES =====
Route::middleware(['guest', 'throttle:10,1'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,1');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ===== ADMIN ROUTES =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/revenue', [\App\Http\Controllers\Admin\RevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/print', [\App\Http\Controllers\Admin\RevenueController::class, 'print'])->name('revenue.print');
    Route::resource('buses', AdminBusController::class);
    Route::resource('drivers', \App\Http\Controllers\Admin\DriverController::class);
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggleStatus');
});

// ===== SOPIR ROUTES =====
Route::middleware(['auth', 'sopir'])->prefix('sopir')->name('sopir.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Sopir\DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/dashboard/status', [\App\Http\Controllers\Sopir\DashboardController::class, 'updateStatus'])->name('dashboard.status');
    Route::post('/dashboard/finish', [\App\Http\Controllers\Sopir\DashboardController::class, 'finishTrip'])->name('dashboard.finish');
    Route::get('/dashboard/tips', [\App\Http\Controllers\Sopir\DashboardController::class, 'checkTips'])->name('dashboard.tips');
    Route::post('/dashboard/report', [\App\Http\Controllers\Sopir\DashboardController::class, 'storeReport'])->name('dashboard.report');
    Route::post('/dashboard/board/{booking}', [\App\Http\Controllers\Sopir\DashboardController::class, 'boardPassenger'])->name('dashboard.board');
});

// ===== USER ROUTES =====
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
    Route::get('/buses', [UserBookingController::class, 'buses'])->name('buses');
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{bus}', [UserBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [UserBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [UserBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/validate', [UserBookingController::class, 'validateCheckIn'])->name('bookings.validate');
    Route::post('/buses/{bus}/tip', [\App\Http\Controllers\User\TipController::class, 'store'])->name('tip.store');
    Route::get('/buses/{bus}/tip/status', [\App\Http\Controllers\User\TipController::class, 'status'])->name('tip.status');
});

// ===== LOCALIZATION ROUTE =====
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('lang.switch');

// ===== NOTIFICATIONS ROUTE =====
Route::middleware('auth')->get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');

// ===== [DEV ONLY] ERROR PAGE TESTING ROUTES =====
// Hanya aktif di environment local. Akses: /test-error/404, /test-error/500, dst.
if (app()->environment('local')) {
    Route::prefix('test-error')->name('test.error.')->group(function () {
        Route::get('/401', fn() => abort(401))->name('401');
        Route::get('/403', fn() => abort(403))->name('403');
        Route::get('/404', fn() => abort(404))->name('404');
        Route::get('/419', fn() => abort(419))->name('419');
        Route::get('/500', fn() => abort(500))->name('500');
        Route::get('/503', fn() => abort(503))->name('503');
        // Dashboard pengujian semua error sekaligus
        Route::get('/', function () {
            $errors = [
                ['code' => 401, 'label' => 'Unauthorized',          'color' => 'bg-slate-100 text-slate-700',   'icon' => 'fa-lock'],
                ['code' => 403, 'label' => 'Forbidden',             'color' => 'bg-orange-100 text-orange-700', 'icon' => 'fa-ban'],
                ['code' => 404, 'label' => 'Not Found',             'color' => 'bg-blue-100 text-blue-700',     'icon' => 'fa-search'],
                ['code' => 419, 'label' => 'Page Expired (CSRF)',   'color' => 'bg-yellow-100 text-yellow-700', 'icon' => 'fa-clock'],
                ['code' => 500, 'label' => 'Server Error',          'color' => 'bg-red-100 text-red-700',       'icon' => 'fa-server'],
                ['code' => 503, 'label' => 'Service Unavailable',   'color' => 'bg-purple-100 text-purple-700', 'icon' => 'fa-tools'],
            ];
            return response()->view('errors.test-dashboard', compact('errors'));
        })->name('dashboard');
    });
}

