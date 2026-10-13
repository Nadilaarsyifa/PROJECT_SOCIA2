<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Menampilkan halaman Analyze Ticket.
     */
    public function create(): View
    {
        return view('tickets.create');
    }
}