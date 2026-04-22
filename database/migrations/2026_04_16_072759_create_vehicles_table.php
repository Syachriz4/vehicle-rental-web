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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number', 20)->unique();
            $table->string('vehicle_name', 100);
            $table->enum('vehicle_type', ['passenger', 'cargo']);
            $table->unsignedBigInteger('region_id');
            $table->string('brand', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->integer('year')->nullable();
            $table->date('purchase_date')->nullable();
            $table->integer('current_km')->default(0);
            $table->date('last_service_date')->nullable();
            $table->enum('status', ['available', 'in_use', 'maintenance'])->default('available');
            $table->boolean('is_rental')->default(false);
            $table->string('rental_company_name', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
