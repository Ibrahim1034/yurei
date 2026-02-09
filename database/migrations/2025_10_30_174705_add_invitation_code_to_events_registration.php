<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\EventRegistration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add the column without the 'after' constraint to avoid the SQL error
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->string('invitation_code')->unique()->nullable();
        });

        // 2. Generate invitation codes for any existing registrations
        // We use a chunk to be safe if there's a lot of data
        EventRegistration::whereNull('invitation_code')
            ->chunkById(100, function ($registrations) {
                foreach ($registrations as $registration) {
                    $registration->update([
                        'invitation_code' => Str::upper(Str::random(8))
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn('invitation_code');
        });
    }
};
