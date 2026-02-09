<?php
// database/migrations/2024_01_01_create_programs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('venue');
            $table->string('image')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->integer('max_participants')->default(0);
            $table->integer('current_participants')->default(0);
            $table->dateTime('registration_deadline')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs');
    }
};