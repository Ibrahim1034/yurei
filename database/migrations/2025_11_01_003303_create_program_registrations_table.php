<?php
// database/migrations/2024_01_01_create_program_registrations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('program_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->string('mpesa_receipt_number')->nullable();
            $table->string('merchant_request_id')->nullable();
            $table->string('checkout_request_id')->nullable();
            $table->string('invitation_code')->nullable();
            $table->dateTime('registration_date');
            $table->string('status')->default('pending'); // pending, confirmed, cancelled, attended
            $table->boolean('is_guest')->default(false);
            $table->boolean('confirmation_email_sent')->default(false);
            $table->timestamps();
            
            $table->index(['program_id', 'user_id']);
            $table->unique(['program_id', 'user_id'], 'unique_user_program');
            $table->unique(['program_id', 'guest_email'], 'unique_guest_program');
        });
    }

    public function down()
    {
        Schema::dropIfExists('program_registrations');
    }
};