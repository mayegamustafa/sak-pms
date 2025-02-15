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
           // Create the properties table
           Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['House', 'Flat']); 
            $table->integer('num_units');
            $table->integer('num_floors')->nullable();
            $table->string('location');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->decimal('default_rent_amount', 10, 2)->default(0); // Default price for all units
            $table->timestamps();
        
        
            // Foreign keys
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade'); // Foreign key for property owner
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null'); // Foreign key for property manager
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
