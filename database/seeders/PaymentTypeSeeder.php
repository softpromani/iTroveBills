<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Cash'],
            ['name' => 'Online'],
            ['name' => 'Cheque'],
            // Add more roles as needed
        ];

        // Insert the roles into the database
        DB::table('payment_types')->insert($types);
    }
}
