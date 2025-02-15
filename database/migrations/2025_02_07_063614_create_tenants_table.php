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
            Schema::create('tenants', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable()->change();
                $table->string('phone_number')->nullable();
                $table->unsignedBigInteger('property_id');
                $table->unsignedBigInteger('unit_id')->nullable();
                $table->date('lease_start_date');
                $table->date('lease_end_date')->nullable();
                $table->decimal('rent_amount', 15, 2);
                $table->boolean('is_active')->default(true);
                 $table->decimal('security_deposit', 10, 2);
                $table->timestamps();
        
                $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
                $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            });
        }
     
   

        
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
