<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert 8 regions: 1 headquarters, 1 branch, 6 mining locations
        DB::table('regions')->insert([
            // Headquarters
            [
                'name' => 'REGION-01',
                'code' => 'RGN-001',
                'description' => 'Main Headquarters',
                'address' => 'Location 1',
                'type' => 'kantor_pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Branch Office
            [
                'name' => 'REGION-02',
                'code' => 'RGN-002',
                'description' => 'Branch Office',
                'address' => 'Location 2',
                'type' => 'kantor_cabang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Mining Locations (6 total)
            [
                'name' => 'REGION-03',
                'code' => 'RGN-003',
                'description' => 'Mining Location 1',
                'address' => 'Location 3',
                'type' => 'tambang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REGION-04',
                'code' => 'RGN-004',
                'description' => 'Mining Location 2',
                'address' => 'Location 4',
                'type' => 'tambang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REGION-05',
                'code' => 'RGN-005',
                'description' => 'Mining Location 3',
                'address' => 'Location 5',
                'type' => 'tambang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REGION-06',
                'code' => 'RGN-006',
                'description' => 'Mining Location 4',
                'address' => 'Location 6',
                'type' => 'tambang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REGION-07',
                'code' => 'RGN-007',
                'description' => 'Mining Location 5',
                'address' => 'Location 7',
                'type' => 'tambang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REGION-08',
                'code' => 'RGN-008',
                'description' => 'Mining Location 6',
                'address' => 'Location 8',
                'type' => 'tambang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        echo "✅ RegionSeeder: 8 regions inserted successfully!\n";
    }
}
