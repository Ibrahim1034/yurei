<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kenya_locations', function (Blueprint $table) {
            $table->id();
            $table->string('county');
            $table->string('constituency');
            $table->string('ward');
            $table->timestamps();
            
            $table->index(['county', 'constituency', 'ward']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kenya_locations');
    }
};