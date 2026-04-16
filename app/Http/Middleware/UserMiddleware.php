<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Kick out suspended accounts immediately, even mid-session
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan oleh Administrator. Silakan hubungi admin.',
                ]);
            }

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->isSopir()) {
                return redirect()->route('sopir.dashboard');
            }
        } else {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
