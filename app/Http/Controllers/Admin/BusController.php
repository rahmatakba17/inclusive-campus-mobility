<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::withCount('bookings')->latest()->paginate(10);
        return view('admin.buses.index', compact('buses'));
    }

    public function create()
    {
        $drivers = \App\Models\User::where('role', 'sopir')->get();
        return view('admin.buses.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'driver_id'      => 'nullable|exists:users,id',
            'plate_number'   => 'required|string|max:20|unique:buses',
            'capacity'       => 'required|integer|min:1|max:100',
            'route'          => 'required|string|max:255',
            'departure_time' => 'required|string',
            'arrival_time'   => 'required|string',
            'description'    => 'nullable|string',
            'status'         => 'required|in:active,maintenance,inactive',
            'maintenance_notes' => 'nullable|required_if:status,maintenance|string|max:1500',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('buses', 'public');
        }

        // Jika tidak ada sopir yang ditugaskan, paksa status menjadi inactive
        if (empty($validated['driver_id'])) {
            $validated['status'] = 'inactive';
        }

        $bus = Bus::create($validated);

        if ($bus->status === 'maintenance' && $request->filled('maintenance_notes')) {
            \App\Models\BusReport::create([
                'bus_id' => $bus->id,
                'user_id' => auth()->id(),
                'type' => 'maintenance',
                'condition' => 'needs_maintenance',
                'notes' => $request->maintenance_notes
            ]);
        }

        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus berhasil ditambahkan!');
    }

    public function show(Bus $bus)
    {
        $bus->load(['bookings.user', 'tips']);
        $stats = [
            'total_bookings' => $bus->bookings->count(),
            'confirmed' => $bus->bookings->where('status', 'confirmed')->count(),
            'pending' => $bus->bookings->where('status', 'pending')->count(),
            'cancelled' => $bus->bookings->where('status', 'cancelled')->count(),
            'total_tips' => $bus->tips->sum('amount'),
        ];
        return view('admin.buses.show', compact('bus', 'stats'));
    }

    public function edit(Bus $bus)
    {
        $drivers = \App\Models\User::where('role', 'sopir')->get();
        return view('admin.buses.edit', compact('bus', 'drivers'));
    }

    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'driver_id'      => 'nullable|exists:users,id',
            'plate_number'   => 'required|string|max:20|unique:buses,plate_number,' . $bus->id,
            'capacity'       => 'required|integer|min:1|max:100',
            'route'          => 'required|string|max:255',
            'departure_time' => 'required|string',
            'arrival_time'   => 'required|string',
            'description'    => 'nullable|string',
            'status'         => 'required|in:active,maintenance,inactive',
            'maintenance_notes' => 'nullable|required_if:status,maintenance|string|max:1500',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($bus->image) {
                Storage::disk('public')->delete($bus->image);
            }
            $validated['image'] = $request->file('image')->store('buses', 'public');
        }

        // Jika tidak ada sopir yang ditugaskan, paksa status menjadi inactive
        if (empty($validated['driver_id'])) {
            $validated['status'] = 'inactive';
        }

        $bus->update($validated);

        // Jika rute/status berubah menjadi maintenance, beri laporan
        if ($bus->status === 'maintenance' && $request->filled('maintenance_notes')) {
            \App\Models\BusReport::create([
                'bus_id' => $bus->id,
                'user_id' => auth()->id(),
                'type' => 'maintenance',
                'condition' => 'needs_maintenance',
                'notes' => $request->maintenance_notes
            ]);
        }

        return redirect()->route('admin.buses.index')
            ->with('success', 'Data bus berhasil diperbarui!');
    }

    public function destroy(Bus $bus)
    {
        if ($bus->status !== 'inactive') {
            return back()->with('error', 'Penghapusan ditolak! Bus hanya dapat dihapus jika statusnya Tidak Aktif.');
        }

        if ($bus->image) {
            Storage::disk('public')->delete($bus->image);
        }
        $bus->delete();
        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus berhasil dihapus!');
    }
}
