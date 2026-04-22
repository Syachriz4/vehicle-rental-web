<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert users: Admin, Approvers (2-level hierarchy), and Staff
        DB::table('users')->insert([
            // ========================================
            // APPROVER USERS (2-level hierarchy)
            // ========================================
            // Level 2 (Top Manager)
            [
                'name' => 'User Manager',
                'email' => 'manager@admin.com',
                'password' => Hash::make('password123'),
                'role' => 'approver',
                'department_id' => 2,
                'supervisor_id' => null,
                'phone' => '081234567891',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Level 1 (Supervisor)
            [
                'name' => 'User Supervisor',
                'email' => 'supervisor@admin.com',
                'password' => Hash::make('password123'),
                'role' => 'approver',
                'department_id' => 1,
                'supervisor_id' => 1,
                'phone' => '081234567892',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // ========================================
            // ADMIN USER (with supervisor hierarchy)
            // ========================================
            [
                'name' => 'User Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'department_id' => 1,
                'supervisor_id' => 2,
                'phone' => '081234567890',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // ========================================
            // REGULAR STAFF / USERS
            // ========================================
            [
                'name' => 'User A',
                'email' => 'usera@user.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'department_id' => 1,
                'supervisor_id' => 3,
                'phone' => '081234567893',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'name' => 'User B',
                'email' => 'userb@user.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'department_id' => 2,
                'supervisor_id' => 3,
                'phone' => '081234567894',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'name' => 'User C',
                'email' => 'userc@user.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'department_id' => 5,
                'supervisor_id' => 2,
                'phone' => '081234567895',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'name' => 'User D',
                'email' => 'userd@user.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'department_id' => 5,
                'supervisor_id' => 2,
                'phone' => '081234567896',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        echo "✅ UserSeeder: 7 users (1 Admin, 2 Approvers, 4 Staff) inserted successfully!\n";
    }
}
