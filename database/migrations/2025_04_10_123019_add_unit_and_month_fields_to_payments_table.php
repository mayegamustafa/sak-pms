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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('set null')->after('tenant_id');
            $table->unsignedTinyInteger('for_month')->nullable()->after('payment_date'); // e.g., 1-12
            $table->unsignedSmallInteger('for_year')->nullable()->after('for_month');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['unit_id', 'for_month', 'for_year']);
        });
    }
};
