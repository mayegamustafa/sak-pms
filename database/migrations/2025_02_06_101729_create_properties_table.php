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
            $table->enum('type', ['House', 'Flat']); // 'House' or 'Flat'
            $table->integer('num_units'); // Number of units in the property
            $table->integer('num_floors')->nullable(); // Number of floors (for flats)
            $table->string('location');
            $table->unsignedBigInteger('owner_id'); // Property owner ID
            $table->unsignedBigInteger('manager_id')->nullable(); // Manager ID (foreign key)
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
