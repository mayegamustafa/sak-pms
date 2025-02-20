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
        Schema::table('tenants', function (Blueprint $table) {
            $table->integer('months_paid')->default(1)->after('security_deposit');
            $table->date('lease_end_date')->nullable()->change();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('months_paid');
            // If lease_end_date was nullable before, you don't need to revert it, but you can if necessary
            // $table->date('lease_end_date')->nullable(false)->change(); //
        });
    }
};
