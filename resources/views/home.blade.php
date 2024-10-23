@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header mb-4">Available Tickets</h1>

            <div class="row card-body">
                @foreach ($tickets as $ticket)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="d-flex align-items-center justify-content-between p-3">
                                <!-- Circular Image -->
                                <img src="{{ asset($ticket->image ?? 'media/default.jpg') }}" class="rounded-circle"
                                    alt="{{ $ticket->name }}" style="width: 150px; height: 150px; object-fit: cover;">

                                <!-- Order Form / Sold-out Button -->
                                <div class="ms-4">
                                    @if ($ticket->available > 0)
                                        <form action="{{ route('orders.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                                            <div class="mb-2">
                                                <label for="quantity-{{ $ticket->id }}"
                                                    class="form-label">Quantity</label>
                                                <input type="number" name="quantity" id="quantity-{{ $ticket->id }}"
                                                    class="form-control" min="1" max="{{ $ticket->available }}"
                                                    required>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Order Ticket</button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary" disabled>Sold-out</button>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">{{ $ticket->name }}</h5>
                                <p class="card-text">{{ $ticket->description }}</p>
                                <p class="card-text"><strong>Price:</strong> ${{ $ticket->price }}</p>
                                <p class="card-text">
                                    <strong>Status:</strong>
                                    @if ($ticket->available > 0)
                                        {{ $ticket->available }} available
                                    @else
                                        <span class="text-danger">Sold-out</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endsection
