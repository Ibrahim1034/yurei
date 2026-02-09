<?php
// database/migrations/2025_09_28_151008_create_mpesa_s_t_k_s_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mpesa_s_t_k_s', function (Blueprint $table) {
            $table->id();
            $table->string('merchant_request_id')->nullable();
            $table->string('checkout_request_id')->nullable();
            $table->string('result_code')->nullable();
            $table->string('result_desc')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('mpesa_receipt_number')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('phonenumber')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->text('failure_reason')->nullable();
            $table->timestamps();
            
            $table->index(['merchant_request_id', 'checkout_request_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mpesa_s_t_k_s');
    }
};