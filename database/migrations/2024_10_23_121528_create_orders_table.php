<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->string('invoice_id')->nullable();
            $table->string('transaction_no')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 8, 2);  // Ticket price per unit
            $table->decimal('grand_total', 10, 2);  // Total price for the order
            $table->string('invoice_url')->nullable();
            $table->enum('status', ['pending', 'paid', 'settled', 'expired', 'cancelled'])->default('pending');
            $table->timestamps();  // Adds created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
