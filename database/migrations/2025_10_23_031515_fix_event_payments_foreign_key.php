<?php
// database/migrations/xxxx_xx_xx_xxxxxx_fix_event_payments_foreign_key.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixEventPaymentsForeignKey extends Migration
{
    public function up()
    {
        // Check if the foreign key exists and is correct
        Schema::table('event_payments', function (Blueprint $table) {
            // Drop existing foreign key if it exists
            $table->dropForeign(['event_registration_id']);
            
            // Re-add the foreign key with proper constraints
            $table->foreign('event_registration_id')
                  ->references('id')
                  ->on('event_registrations')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('event_payments', function (Blueprint $table) {
            $table->dropForeign(['event_registration_id']);
        });
    }
}