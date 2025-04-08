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
    Schema::table('tenants', function (Blueprint $table) {
        $table->date('payment_date')->nullable(); // Or use 'timestamp' if that's more appropriate
    });
}

public function down()
{
    Schema::table('tenants', function (Blueprint $table) {
        $table->dropColumn('payment_date');
    });
}

};
