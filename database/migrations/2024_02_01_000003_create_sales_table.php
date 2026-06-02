<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('customer_name')->nullable();
            // Si la venta lleva correo del cliente, el ticket también se le envía a él.
            $table->string('customer_email')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('payment_method');
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
