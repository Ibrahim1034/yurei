<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_confirmation_email_sent_to_event_registrations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfirmationEmailSentToEventRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->boolean('confirmation_email_sent')->default(false)->after('is_guest');
        });
    }

    public function down()
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn('confirmation_email_sent');
        });
    }
}