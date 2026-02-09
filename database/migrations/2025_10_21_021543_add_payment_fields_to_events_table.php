<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_payment_fields_to_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false);
            $table->decimal('registration_fee', 8, 2)->default(0);
            $table->integer('max_participants')->default(0);
            $table->integer('current_participants')->default(0);
            $table->timestamp('registration_deadline')->nullable();
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'is_paid',
                'registration_fee',
                'max_participants',
                'current_participants',
                'registration_deadline'
            ]);
        });
    }
};