<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Order;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        return view("orders.index", compact('orders'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $ticket = Ticket::find($request->ticket_id);
        if ($ticket->available > 0) {
            $url = '';
            $price = $ticket->price;
            $grandTotal = $price * $request->quantity;

            $order = Order::create([
                'ticket_id' => $ticket->id,
                'quantity' => $request->quantity,
                'price' => $price,
                'grand_total' => $grandTotal,
                'status' => 'pending',
            ]);

            // make xendit invoice
            return redirect()->away($url);
        }

        return redirect()->back()->with('error', 'Ticket is not avialable. Try again!');
    }
}
