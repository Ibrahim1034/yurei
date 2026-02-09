<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnInEventRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'attended', 'no_show'])
                  ->default('pending')
                  ->change();
        });
    }

    public function down()
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }
}