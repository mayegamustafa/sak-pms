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
        $table->unsignedBigInteger('property_id');  // Link to properties
        $table->string('unit_number');              // E.g., Flat A1, Room 3B
        //$table->string('tenant_name')->nullable(); // Null if vacant
        $table->integer('floor')->nullable();       // Optional: For multi-story buildings
        $table->decimal('rent_amount', 15, 2); // Amount in UGX
        $table->enum('status', ['Occupied', 'Vacant'])->default('Vacant');
        $table->timestamps();

        // Foreign key constraint to link units to properties
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
