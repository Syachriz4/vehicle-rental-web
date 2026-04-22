<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder dalam urutan yang benar
        // (karena ada foreign key dependencies)
        
        echo "\n========== SEEDING DATABASE ==========\n";
        
        // 1. Seed Regions terlebih dahulu (foundational)
        $this->call(RegionSeeder::class);
        
        // 2. Seed Departments (FK ke regions)
        $this->call(DepartmentSeeder::class);
        
        // 3. Seed Users (FK ke departments & users)
        $this->call(UserSeeder::class);
        
        // 4. Seed Vehicles (FK ke regions)
        $this->call(VehicleSeeder::class);
        
        // 5. Seed Service Schedules (FK ke vehicles)
        $this->call(ServiceScheduleSeeder::class);
        
        echo "========== SEEDING COMPLETED! ==========\n";
        echo "✅ Database sudah berisi sample data\n";
        echo "✅ Siap untuk testing & development\n\n";
    }
}
