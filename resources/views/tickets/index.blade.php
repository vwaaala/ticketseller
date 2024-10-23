@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header mb-4">Tickets</div>
        <div class="card-body">
            <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Add Ticket</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Available</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->name }}</td>
                            <td>${{ $ticket->price }}</td>
                            <td>{{ $ticket->total }}</td>
                            <td>{{ $ticket->available }}</td>
                            <td>{{ $ticket->status ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
