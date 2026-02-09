<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Make optional fields nullable
            $table->string('membership_number')->nullable()->change();
            $table->date('registration_date')->nullable()->change();
            $table->date('expiration_date')->nullable()->change();
            $table->string('profile_picture')->nullable()->change();
            $table->string('phone_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('membership_number')->nullable(false)->change();
            $table->date('registration_date')->nullable(false)->change();
            $table->date('expiration_date')->nullable(false)->change();
            $table->string('profile_picture')->nullable(false)->change();
            $table->string('phone_number')->nullable(false)->change();
        });
    }
};