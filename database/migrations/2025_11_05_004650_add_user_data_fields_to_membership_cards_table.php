<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('membership_cards', function (Blueprint $table) {
            // Add user_type column
            if (!Schema::hasColumn('membership_cards', 'user_type')) {
                $table->enum('user_type', ['member', 'friend'])->default('member')->after('user_id');
            }

            // Add county column
            if (!Schema::hasColumn('membership_cards', 'county')) {
                $table->string('county')->nullable()->after('user_type');
            }

            // Add constituency column
            if (!Schema::hasColumn('membership_cards', 'constituency')) {
                $table->string('constituency')->nullable()->after('county');
            }

            // Add ward column
            if (!Schema::hasColumn('membership_cards', 'ward')) {
                $table->string('ward')->nullable()->after('constituency');
            }

            // Add institution column
            if (!Schema::hasColumn('membership_cards', 'institution')) {
                $table->string('institution')->nullable()->after('ward');
            }

            // Add graduation_status column
            if (!Schema::hasColumn('membership_cards', 'graduation_status')) {
                $table->string('graduation_status')->nullable()->after('institution');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership_cards', function (Blueprint $table) {
            // Remove the columns if they were added by this migration
            $columnsToDrop = ['user_type', 'county', 'constituency', 'ward', 'institution', 'graduation_status'];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('membership_cards', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};