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
    Schema::table('payments', function (Blueprint $table) {
        $table->string('invoice_id')->change(); // Change the column to string (VARCHAR)
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->integer('invoice_id')->change(); // Revert to integer if needed
    });
}

};
