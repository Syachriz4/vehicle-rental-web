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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 50)->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('approver1_id')->nullable();
            $table->unsignedBigInteger('approver2_id')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('actual_return_date')->nullable();
            $table->string('purpose', 255);
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->integer('fuel_used')->nullable();
            $table->integer('start_km')->nullable();
            $table->integer('end_km')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('restrict');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approver1_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approver2_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
