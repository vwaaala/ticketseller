@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header mb-4">All Orders</div>
        <table class="table table-striped card-body">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Ticket Name</th>
                    <th>Quantity</th>
                    <th>Grand Total</th>
                    <th>Status</th>
                    <th>Invoice</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->ticket->name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>${{ number_format($order->grand_total, 2) }}</td>
                        <td>
                            <span
                                class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'expired' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            @if ($order->invoice_url)
                                <a href="{{ $order->invoice_url }}" class="btn btn-sm btn-info" target="_blank">View
                                    Invoice</a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
