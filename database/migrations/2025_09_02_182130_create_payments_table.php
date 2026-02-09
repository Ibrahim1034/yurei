<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/2024_..._create_payments_table.php
public function up(): void
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users.id
        $table->string('merchant_request_id');
        $table->string('checkout_request_id');
        $table->string('mpesa_receipt_number')->nullable();
        $table->double('amount');
        $table->string('phone_number'); // The number that made the payment
        $table->timestamp('transaction_date')->nullable();
        // Using string for status for simplicity. Alternatively, you could use an ENUM type.
        $table->string('status')->default('pending'); // 'pending', 'completed', 'cancelled', 'failed'
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('payments');
}
};
