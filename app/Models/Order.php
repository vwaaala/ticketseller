<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'invoice_id',
        'transaction_no',
        'quantity',
        'price',
        'grand_total',
        'invoice_url',
        'status',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
