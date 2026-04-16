<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'sopir')->with('bus');

        if ($request->has('search') && $request->search !== '') {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }

        $drivers = $query->latest()->paginate(15);
        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('admin.drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'sopir',
        ]);

        return redirect()->route('admin.drivers.index')->with('success', 'Sopir armada berhasil didaftarkan.');
    }

    public function edit(User $driver)
    {
        if ($driver->role !== 'sopir') {
            abort(404);
        }
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, User $driver)
    {
        if ($driver->role !== 'sopir') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($driver->id)],
            'password' => 'nullable|min:8|confirmed',
        ]);

        $driver->name = $validated['name'];
        $driver->email = $validated['email'];
        if ($request->filled('password')) {
            $driver->password = Hash::make($validated['password']);
        }
        $driver->save();

        return redirect()->route('admin.drivers.index')->with('success', 'Data sopir berhasil diperbarui.');
    }

    public function destroy(User $driver)
    {
        if ($driver->role !== 'sopir') {
            abort(404);
        }
        
        // Remove driver_id from buses if attached
        if ($driver->bus) {
            $driver->bus->update(['driver_id' => null]);
        }

        $driver->delete();
        return redirect()->route('admin.drivers.index')->with('success', 'Akun sopir berhasil dihapus permanen.');
    }
}
