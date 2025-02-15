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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('unit_number');
            $table->integer('floor')->nullable();
            $table->enum('unit_category', ['Single', 'Double', 'Self-contained'])->default('Single');
            $table->decimal('rent_amount', 10, 2)->nullable(); // Can be null to use property default
            $table->enum('status', ['Vacant', 'Occupied'])->default('Vacant');
            $table->timestamps();
        
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
        


        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
