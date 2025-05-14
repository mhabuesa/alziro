<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bkash_payment_records', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->nullable();
            $table->string('trxID')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('cart_customer_id')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('shipping_cost')->nullable();
            $table->string('address')->nullable();
            $table->string('discount')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bkash_payment_records');
    }
};





