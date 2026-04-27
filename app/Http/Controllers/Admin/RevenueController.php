<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'yearly');

        $query = Booking::whereIn('status', ['confirmed', 'cancelled']);
        $chartTitle = "Grafik Pemasukan";

        if ($period === 'yearly') {
            $year = $request->get('year', date('Y'));
            $query->whereYear('created_at', $year);
            $periodeLabel = "Tahun $year";
            
            $chartData = (clone $query)->selectRaw('MONTH(created_at) as label, SUM(price) as total')
                ->groupBy('label')->get()->keyBy('label');
            $labels = []; $totals = [];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create()->month($i)->translatedFormat('M');
                $totals[] = $chartData->has($i) ? $chartData[$i]->total : 0;
            }
        } elseif ($period === 'monthly') {
            $monthInput = $request->get('month', date('Y-m'));
            if ($monthInput === 'all') $monthInput = date('Y-m'); // Fallback in case old cache is present 
            
            $dateObj = Carbon::createFromFormat('Y-m', $monthInput);
            $query->whereYear('created_at', $dateObj->year)
                  ->whereMonth('created_at', $dateObj->month);
            $chartTitle = "Grafik Pemasukan (Bulanan " . $dateObj->translatedFormat('F Y') . ")";
            $periodeLabel = "Bulan " . $dateObj->translatedFormat('F Y');
            
            $chartData = (clone $query)->selectRaw('DAY(created_at) as label, SUM(price) as total')
                ->groupBy('label')->get()->keyBy('label');
            $labels = []; $totals = [];
            for ($i = 1; $i <= $dateObj->daysInMonth; $i++) {
                $labels[] = (string)$i;
                $totals[] = $chartData->has($i) ? $chartData[$i]->total : 0;
            }
        } elseif ($period === 'weekly') {
            $weekInput = $request->get('week', date('Y-\WW'));
            $parts = explode('-W', $weekInput);
            if (count($parts) == 2) {
                $year = $parts[0];
                $week = $parts[1];
                $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $endOfWeek = $startOfWeek->copy()->endOfWeek();
                
                $query->whereBetween('created_at', [
                    $startOfWeek->format('Y-m-d 00:00:00'), 
                    $endOfWeek->format('Y-m-d 23:59:59')
                ]);
                $chartTitle = "Grafik Pemasukan (Minggu ke-$week, $year)";
                $periodeLabel = "Minggu ke-$week, Tahun $year (" . $startOfWeek->format('d/m/Y') . " - " . $endOfWeek->format('d/m/Y') . ")";
                
                $chartData = (clone $query)->selectRaw('DAYOFWEEK(created_at) as label, SUM(price) as total')
                    ->groupBy('label')->get()->keyBy('label');
                
                $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                $totals = [
                    $chartData->has(2) ? $chartData[2]->total : 0, // Mon
                    $chartData->has(3) ? $chartData[3]->total : 0, // Tue
                    $chartData->has(4) ? $chartData[4]->total : 0, // Wed
                    $chartData->has(5) ? $chartData[5]->total : 0, // Thu
                    $chartData->has(6) ? $chartData[6]->total : 0, // Fri
                    $chartData->has(7) ? $chartData[7]->total : 0, // Sat
                    $chartData->has(1) ? $chartData[1]->total : 0, // Sun
                ];
            } else {
                $labels = []; $totals = [];
            }
        } elseif ($period === 'daily') {
            $dateInput = $request->get('date', date('Y-m-d'));
            $dateObj = Carbon::parse($dateInput);
            $query->whereDate('created_at', $dateObj);
            $chartTitle = "Grafik Pemasukan (Harian " . $dateObj->translatedFormat('d M Y') . ")";
            $periodeLabel = "Hari " . $dateObj->translatedFormat('d F Y');
            
            $chartData = (clone $query)->selectRaw('HOUR(created_at) as label, SUM(price) as total')
                ->groupBy('label')->get()->keyBy('label');
            $labels = []; $totals = [];
            for ($i = 0; $i <= 23; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $totals[] = $chartData->has($i) ? $chartData[$i]->total : 0;
            }
        }

        // --- STATS RINGKASAN ---
        $stats = [
            'total_revenue' => (int) (clone $query)->sum('price'),
            'total_qris'    => (int) (clone $query)->where('payment_method', 'qris')->sum('price'),
            'total_etoll'   => (int) (clone $query)->where('payment_method', 'etoll')->sum('price'),
            'total_free'    => (int) (clone $query)->where('price', 0)->count(),
            'total_paid_tickets' => (int) (clone $query)->where('price', '>', 0)->count(),
        ];

        // --- DATA TABEL ---
        // Paginated data untuk riwayat pendapatan (tiket dibayar > Rp 0)
        $revenues = (clone $query)
            ->where('price', '>', 0)
            ->with(['bus', 'user'])
            ->latest('booking_date')
            ->paginate(20)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'stats' => $stats,
                'labels' => $labels,
                'totals' => $totals,
                'chartTitle' => $chartTitle,
                'revenues' => view('admin.revenue.partials.revenue-feed', compact('revenues'))->render(),
            ]);
        }

        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('Y-m'));
        $week = $request->get('week', date('Y-\WW'));
        $date = $request->get('date', date('Y-m-d'));

        return view('admin.revenue.index', compact(
            'stats', 'labels', 'totals', 'revenues', 
            'period', 'year', 'month', 'week', 'date', 'chartTitle'
        ));
    }

    public function print(Request $request)
    {
        $period = $request->get('period', 'yearly');
        $query = Booking::whereIn('status', ['confirmed', 'cancelled']);
        $periodeLabel = "Semua Waktu";

        if ($period === 'yearly') {
            $year = $request->get('year', date('Y'));
            $query->whereYear('created_at', $year);
            $periodeLabel = "Tahun $year";
        } elseif ($period === 'monthly') {
            $monthInput = $request->get('month', date('Y-m'));
            if ($monthInput === 'all') $monthInput = date('Y-m');
            
            $dateObj = Carbon::createFromFormat('Y-m', $monthInput);
            $query->whereYear('created_at', $dateObj->year)
                  ->whereMonth('created_at', $dateObj->month);
            $periodeLabel = "Bulan " . $dateObj->translatedFormat('F Y');
        } elseif ($period === 'weekly') {
            $weekInput = $request->get('week', date('Y-\WW'));
            $parts = explode('-W', $weekInput);
            if (count($parts) == 2) {
                $year = $parts[0];
                $week = $parts[1];
                $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $endOfWeek = $startOfWeek->copy()->endOfWeek();
                $query->whereBetween('created_at', [$startOfWeek->format('Y-m-d 00:00:00'), $endOfWeek->format('Y-m-d 23:59:59')]);
                $periodeLabel = "Minggu ke-$week, Tahun $year (" . $startOfWeek->format('d/m/Y') . " - " . $endOfWeek->format('d/m/Y') . ")";
            }
        } elseif ($period === 'daily') {
            $dateInput = $request->get('date', date('Y-m-d'));
            $dateObj = Carbon::parse($dateInput);
            $query->whereDate('created_at', $dateObj);
            $periodeLabel = "Hari " . $dateObj->translatedFormat('d F Y');
        }

        $stats = [
            'total_revenue' => (int) (clone $query)->sum('price'),
            'total_qris'    => (int) (clone $query)->where('payment_method', 'qris')->sum('price'),
            'total_etoll'   => (int) (clone $query)->where('payment_method', 'etoll')->sum('price'),
            'total_paid_tickets' => (int) (clone $query)->where('price', '>', 0)->count(),
        ];

        // Ambil SEMUA data pendapatan > 0 untuk di print (tanpa pagination)
        $revenues = (clone $query)
            ->where('price', '>', 0)
            ->with(['bus', 'user'])
            ->latest('booking_date')
            ->get();

        return view('admin.revenue.print', compact(
            'stats', 'revenues', 'period', 'periodeLabel'
        ));
    }
}
