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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
