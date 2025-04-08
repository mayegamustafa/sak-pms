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
            $table->decimal('amount_due', 10, 2);  // You can change the type and size as per your requirement
        });
    }
    
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('amount_due');
        });
    }
    
};
