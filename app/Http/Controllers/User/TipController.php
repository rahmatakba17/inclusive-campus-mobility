<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipController extends Controller
{
    public function store(Request $request, Bus $bus)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000|max:5000',
        ]);

        $userId = Auth::id();

        // Cek apakah user sudah memberikan tip dalam 7 hari terakhir (seminggu)
        $tipThisWeek = Tip::where('user_id', $userId)
            ->where('created_at', '>=', now()->startOfWeek()) // Senin minggu ini
            ->first();

        if ($tipThisWeek) {
            $resetDate = now()->endOfWeek()->translatedFormat('l, d M Y'); // Minggu depan
            $msg = 'Anda sudah memberikan tip minggu ini. Tip berikutnya bisa diberikan setelah ' . $resetDate . '.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        Tip::create([
            'bus_id'  => $bus->id,
            'user_id' => $userId,
            'amount'  => $request->amount,
        ]);

        $msg = 'Terima kasih atas apresiasi Anda! Tip sebesar Rp ' . number_format($request->amount, 0, ',', '.') . ' telah dikirimkan secara anonim ke sopir.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Cek status tip user ini untuk sesi berjalan (untuk ditampilkan di UI)
     */
    public function status(Bus $bus)
    {
        $userId = Auth::id();
        $tipThisWeek = Tip::where('user_id', $userId)
            ->where('created_at', '>=', now()->startOfWeek())
            ->first();

        return response()->json([
            'can_tip'     => is_null($tipThisWeek),
            'tip_given'   => !is_null($tipThisWeek),
            'reset_date'  => now()->endOfWeek()->translatedFormat('l, d M Y'),
        ]);
    }
}

