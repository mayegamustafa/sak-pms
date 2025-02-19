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
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('security_deposit', 10, 2)->nullable(); // Adjust type/size as needed
        });
    }
    
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('security_deposit');
        });
    }
    
};
