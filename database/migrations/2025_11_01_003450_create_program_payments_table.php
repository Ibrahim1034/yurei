<?php
// database/migrations/2024_01_01_create_program_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('program_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_registration_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('phone_number');
            $table->string('merchant_request_id');
            $table->string('checkout_request_id');
            $table->string('mpesa_receipt_number')->nullable();
            $table->string('result_code')->nullable();
            $table->string('result_desc')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('program_payments');
    }
};