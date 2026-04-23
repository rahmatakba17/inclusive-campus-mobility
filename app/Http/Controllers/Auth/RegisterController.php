<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100', 'regex:/^[\pL\s\.\-]+$/u'],
            'email'    => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'password' => [
                'required', 'string', 'min:8', 'max:128', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', // wajib huruf besar, kecil, dan angka
            ],
        ], [
            'password.regex' => 'Password wajib mengandung minimal 1 huruf kapital, 1 huruf kecil, dan 1 angka.',
            'name.regex'     => 'Nama hanya boleh mengandung huruf, spasi, titik, dan tanda hubung.',
        ]);

        $role = str_ends_with(strtolower($validated['email']), '@kampus-non-merdeka.ac.id') ? 'civitas' : 'umum';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
        ]);

        // Auth::login($user); // Di-disable karena wajib konfirmasi dari Admin

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil. Akun Anda sedang diverifikasi oleh Administrator sebelum dapat digunakan.');
    }
}
