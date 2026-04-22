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
        Schema::create('service_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('restrict');
            $table->enum('service_type', ['maintenance', 'inspection', 'oil_change', 'tire_replacement', 'filter_replacement', 'coolant_replacement', 'other']);
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->integer('estimated_cost')->nullable(); // Rp
            $table->integer('actual_cost')->nullable(); // Rp
            $table->text('notes')->nullable();
            $table->text('completion_notes')->nullable();
            $table->timestamps();
            
            // Indexes for common queries
            $table->index('vehicle_id');
            $table->index('status');
            $table->index('scheduled_date');
            $table->index('completed_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_schedules');
    }
};
