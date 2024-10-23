<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::all();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tickets,name,',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'total' => 'required|integer',
            'available' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = str()->uuid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('media'), $imageName);
            $data['image'] = 'media/' . $imageName;
        }

        Ticket::create($data);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tickets,name,' . $ticket->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'total' => 'required|integer',
            'available' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Optional: Delete old image if it exists
            if ($ticket->image && file_exists(public_path($ticket->image))) {
                unlink(public_path($ticket->image));
            }

            $image = $request->file('image');
            $imageName = str()->uuid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('media'), $imageName);
            $data['image'] = 'media/' . $imageName;
        }

        $ticket->update($data);

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}
