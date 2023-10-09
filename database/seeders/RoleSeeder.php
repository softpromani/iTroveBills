<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = [
            ['name' => 'Admin', 'guard_name' => 'web'],
            ['name' => 'Customer', 'guard_name' => 'web'],
            ['name' => 'Seller', 'guard_name' => 'web'],
            // Add more roles as needed
        ];

        // Insert the roles into the database
        DB::table('roles')->insert($roles);
    }
}
