<?php

namespace Database\Seeders;

use App\Models\ServiceSchedule;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class ServiceScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = Vehicle::all();

        $serviceTypes = ['maintenance', 'inspection', 'oil_change', 'tire_replacement', 'filter_replacement', 'coolant_replacement'];

        // Create service schedules for each vehicle
        foreach ($vehicles as $vehicle) {
            // Past service (completed)
            ServiceSchedule::create([
                'vehicle_id' => $vehicle->id,
                'service_type' => 'maintenance',
                'scheduled_date' => now()->subMonths(3),
                'completed_date' => now()->subMonths(3)->addDays(1),
                'status' => 'completed',
                'estimated_cost' => 500000,
                'actual_cost' => 520000,
                'notes' => 'Regular maintenance service',
                'completion_notes' => 'Service completed successfully',
            ]);

            // Upcoming overdue service
            ServiceSchedule::create([
                'vehicle_id' => $vehicle->id,
                'service_type' => 'oil_change',
                'scheduled_date' => now()->subDays(10),
                'status' => 'pending',
                'estimated_cost' => 300000,
                'notes' => 'Oil and filter change due',
            ]);

            // Upcoming service (within 7 days)
            ServiceSchedule::create([
                'vehicle_id' => $vehicle->id,
                'service_type' => 'inspection',
                'scheduled_date' => now()->addDays(3),
                'status' => 'pending',
                'estimated_cost' => 200000,
                'notes' => 'Regular inspection check',
            ]);

            // Future service
            ServiceSchedule::create([
                'vehicle_id' => $vehicle->id,
                'service_type' => $serviceTypes[array_rand($serviceTypes)],
                'scheduled_date' => now()->addMonths(1),
                'status' => 'pending',
                'estimated_cost' => rand(200000, 1000000),
                'notes' => 'Scheduled preventive maintenance',
            ]);
        }
    }
}
