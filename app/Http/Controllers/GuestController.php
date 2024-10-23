<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class GuestController extends Controller
{
    public function index()
    {
        // Get all tickets where available > 0 or sold out
        $tickets = Ticket::where('status', true)->get();
        return view('home', compact('tickets'));
    }
}
