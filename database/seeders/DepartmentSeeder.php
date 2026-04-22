<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert 8 departments across regions
        DB::table('departments')->insert([
            // Headquarters Departments (region 1)
            [
                'name' => 'DEPT-01',
                'code' => 'DPT-001',
                'location' => 'Jakarta Office',
                'head_name' => 'Bambang Sutrisno',
                'description' => 'Department A',
                'region_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DEPT-02',
                'code' => 'DPT-002',
                'location' => 'Jakarta Office',
                'head_name' => 'Siti Nurhaliza',
                'description' => 'Department B',
                'region_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DEPT-03',
                'code' => 'DPT-003',
                'location' => 'Jakarta Office',
                'head_name' => 'Adi Pratama',
                'description' => 'Department C',
                'region_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DEPT-04',
                'code' => 'DPT-004',
                'location' => 'Jakarta Office',
                'head_name' => 'Citra Dewi',
                'description' => 'Department D',
                'region_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Branch Department (region 2)
            [
                'name' => 'DEPT-05',
                'code' => 'DPT-005',
                'location' => 'Surabaya Office',
                'head_name' => 'Rudi Hartono',
                'description' => 'Department E',
                'region_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Mining Location Departments
            [
                'name' => 'DEPT-06',
                'code' => 'DPT-006',
                'location' => 'Kalimantan Site',
                'head_name' => 'Hendra Wijaya',
                'description' => 'Department F',
                'region_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DEPT-07',
                'code' => 'DPT-007',
                'location' => 'Kalimantan Site',
                'head_name' => 'Putri Kusuma',
                'description' => 'Department G',
                'region_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DEPT-08',
                'code' => 'DPT-008',
                'location' => 'Papua Site',
                'head_name' => 'Joko Santoso',
                'description' => 'Department H',
                'region_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        echo "✅ DepartmentSeeder: 8 departments inserted successfully!\n";
    }
}
