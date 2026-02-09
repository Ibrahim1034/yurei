<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_invoice_id_to_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('invoice_id')->nullable()->after('checkout_request_id');
            $table->string('failure_reason')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
            $table->dropColumn('failure_reason');
        });
    }
};