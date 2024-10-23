<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Order;
class OrderController extends Controller
{
    public function __construct()
    {
        \Xendit\Configuration::setXenditKey(config('services.xendit.private'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::paginate(10);
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


        $ticket = Ticket::findOrFail($request->ticket_id);
        if ($ticket->available > 0) {
            if ($ticket->available < $request->quantity) {
                return back()->with('error', 'Not enough tickets available.');
            }
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

            // $price = $ticket->price;
            $price = 13579;
            // $grandTotal = $price * $request->quantity;
            $grandTotal = 13579;

            // Create Xendit invoice
            $apiInstance = new \Xendit\Invoice\InvoiceApi();
            $createInvoiceRequest = new \Xendit\Invoice\CreateInvoiceRequest([
                'external_id' => 'OrderID-' . strval($order->id),
                'amount' => $grandTotal,
                'description' => "Order for {$ticket->name}",
                'invoice_duration' => 3600,  // Expires in 1 hour
                'success_redirect_url' => route('home'),
                'failure_redirect_url' => route('home'),
            ]);

            try {
                $invoice = $apiInstance->createInvoice($createInvoiceRequest);

                // Create the order in the database
                $order->update([
                    'invoice_id' => $invoice->getId(),
                    'invoice_url' => $invoice->getInvoiceUrl(),
                ]);

                // Decrease the available ticket count
                $ticket->decrement('available', $request->quantity);

                // Redirect to the Xendit invoice URL
                return redirect($invoice->getInvoiceUrl());
            } catch (\Xendit\XenditSdkException $e) {
                return back()->with('error', 'Failed to create invoice: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Ticket is not avialable. Try again!');
    }

    public function webhook(Request $request)
    {
        // Log the webhook payload for debugging (optional)
        \Log::info('Xendit Webhook Payload:', $request->all());

        // Validate the webhook signature (optional but recommended)
        $secret = config('services.xendit.webhook'); // Store this in .env
        $signature = $request->header('X-Ccallback-Token');

        if ($secret && $signature !== $secret) {
            \Log::warning('Invalid webhook signature');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Process the webhook event
        $event = $request->input('status');
        $externalId = $request->input('external_id');
        $externalId = explode('OrderID-', $externalId);
        $externalId = $externalId[1];
        $order = Order::where('transaction_no', $externalId)->first();

        if (!$order) {
            \Log::error('Order not found for external_id: ' . $externalId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($event) {
            case 'PAID':
                $order->update(['status' => 'paid']);
                break;

            case 'SETTLED':
                $order->update(['status' => 'settled']);
                $order->ticket->increment('available', $order->quantity);
                break;

            case 'EXPIRED':
                $order->update(['status' => 'expired']);
                $order->ticket->increment('available', $order->quantity);
                break;

            case 'CANCELLED':
                $order->update(['status' => 'cancelled']);
                $order->ticket->increment('available', $order->quantity);
                break;

            default:
                \Log::info('Unhandled webhook event: ' . $event);
        }

        return response()->json(['message' => 'Webhook handled'], 200);
    }
}
