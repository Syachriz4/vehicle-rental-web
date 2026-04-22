<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert 6 vehicles: 4 company-owned (2 passenger + 2 cargo), 2 rental
        DB::table('vehicles')->insert([
            // ========================================
            // COMPANY-OWNED - PASSENGER VEHICLES
            // ========================================
            [
                'plate_number' => 'VEH-001',
                'vehicle_name' => 'Passenger Vehicle 1',
                'vehicle_type' => 'passenger',
                'region_id' => 1,
                'brand' => 'Brand A',
                'model' => 'Model A',
                'year' => 2021,
                'purchase_date' => '2021-05-15',
                'current_km' => 45230,
                'last_service_date' => '2024-01-10',
                'status' => 'available',
                'is_rental' => false,
                'rental_company_name' => null,
                'notes' => 'Company-owned passenger vehicle',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'plate_number' => 'VEH-002',
                'vehicle_name' => 'Passenger Vehicle 2',
                'vehicle_type' => 'passenger',
                'region_id' => 1,
                'brand' => 'Brand B',
                'model' => 'Model B',
                'year' => 2020,
                'purchase_date' => '2020-08-20',
                'current_km' => 62450,
                'last_service_date' => '2023-12-15',
                'status' => 'available',
                'is_rental' => false,
                'rental_company_name' => null,
                'notes' => 'Company-owned passenger vehicle',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // ========================================
            // COMPANY-OWNED - CARGO VEHICLES
            // ========================================
            [
                'plate_number' => 'VEH-003',
                'vehicle_name' => 'Cargo Vehicle 1',
                'vehicle_type' => 'cargo',
                'region_id' => 2,
                'brand' => 'Brand C',
                'model' => 'Model C',
                'year' => 2019,
                'purchase_date' => '2019-11-10',
                'current_km' => 128900,
                'last_service_date' => '2024-01-05',
                'status' => 'available',
                'is_rental' => false,
                'rental_company_name' => null,
                'notes' => 'Company-owned cargo vehicle',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'plate_number' => 'VEH-004',
                'vehicle_name' => 'Cargo Vehicle 2',
                'vehicle_type' => 'cargo',
                'region_id' => 3,
                'brand' => 'Brand D',
                'model' => 'Model D',
                'year' => 2022,
                'purchase_date' => '2022-03-08',
                'current_km' => 32150,
                'last_service_date' => '2024-01-15',
                'status' => 'available',
                'is_rental' => false,
                'rental_company_name' => null,
                'notes' => 'Company-owned cargo vehicle',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // ========================================
            // RENTAL VEHICLES
            // ========================================
            [
                'plate_number' => 'VEH-005',
                'vehicle_name' => 'Rental Vehicle 1',
                'vehicle_type' => 'passenger',
                'region_id' => 1,
                'brand' => 'Brand E',
                'model' => 'Model E',
                'year' => 2023,
                'purchase_date' => null,
                'current_km' => 8500,
                'last_service_date' => '2024-01-12',
                'status' => 'available',
                'is_rental' => true,
                'rental_company_name' => 'Rental Company A',
                'notes' => 'Rented vehicle for events',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'plate_number' => 'VEH-006',
                'vehicle_name' => 'Rental Vehicle 2',
                'vehicle_type' => 'cargo',
                'region_id' => 4,
                'brand' => 'Brand F',
                'model' => 'Model F',
                'year' => 2021,
                'purchase_date' => null,
                'current_km' => 45600,
                'last_service_date' => '2024-01-08',
                'status' => 'available',
                'is_rental' => true,
                'rental_company_name' => 'Rental Company B',
                'notes' => 'Rented vehicle for transport',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        echo "✅ VehicleSeeder: 6 vehicles (4 company-owned, 2 rental) inserted successfully!\n";
    }
}
