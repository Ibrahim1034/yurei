<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/2024_..._add_columns_to_users_table.php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Add new columns
        $table->string('phone_number')->unique()->after('email');
        $table->string('profile_picture')->nullable()->after('phone_number');
        $table->string('membership_number')->unique()->after('profile_picture');
        $table->date('registration_date')->after('membership_number');
        $table->date('expiration_date')->after('registration_date');
    });
}

// The down() method is used to reverse the migration (if we roll back)
public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Reverse the changes: drop the columns we added
        $table->dropColumn([
            'phone_number',
            'profile_picture',
            'membership_number',
            'registration_date',
            'expiration_date'
        ]);
    });
}
};
