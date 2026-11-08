<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'period' => ['sometimes', 'required', 'in:all,month,day'],
            'month'  => ['required_if:period,month', 'nullable', 'date_format:Y-m'],
            'date'   => ['required_if:period,day', 'nullable', 'date_format:Y-m-d'],
        ]);

        // Default periode adalah bulan ini
        $period = $validated['period'] ?? 'month';

        // Default bulan dan tanggal jika belum dikirim request
        $selectedMonth = $validated['month'] ?? now()->format('Y-m');
        $selectedDate  = $validated['date'] ?? now()->format('Y-m-d');

        // Query dasar tiket
        $tickets = Ticket::query();

        // Filter berdasarkan event_time
        if ($period === 'month') {
            [$year, $month] = explode('-', $selectedMonth);

            $tickets->whereYear('event_time', $year)
                ->whereMonth('event_time', $month);
        } elseif ($period === 'day') {
            $tickets->whereDate('event_time', $selectedDate);
        }

        // Total tiket sesuai periode
        $totalTickets = (clone $tickets)->count();

        // Komposisi Detection (Agregasi langsung via SQL)
        $detectionStats = (clone $tickets)
            ->selectRaw("COALESCE(NULLIF(TRIM(detection), ''), 'Tidak tercatat') AS label, COUNT(*) AS total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        // Distribusi Risk Level (Agregasi langsung via SQL)
        $riskStats = (clone $tickets)
            ->selectRaw("COALESCE(NULLIF(TRIM(risk_level), ''), 'Tidak tercatat') AS label, COUNT(*) AS total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        return view('dashboard', compact(
            'period',
            'selectedMonth',
            'selectedDate',
            'totalTickets',
            'detectionStats',
            'riskStats'
        ));
    }
}