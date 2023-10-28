<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = [
            ['status' => 'active', 'for_module' => 'Customer', 'icon' => '#2323232', 'text_color' => 'green','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'inactive', 'for_module' => 'Customer', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'active', 'for_module' => 'Company', 'icon' => '#2323232', 'text_color' => 'green','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'inactive', 'for_module' => 'Company', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'registerd', 'for_module' => 'Company', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'active', 'for_module' => 'Seller', 'icon' => '#2323232', 'text_color' => 'green','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'inactive', 'for_module' => 'Seller', 'icon' => '#2323232', 'text_color' => 'yellow','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'block', 'for_module' => 'Seller', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'block', 'for_module' => 'Company', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'active', 'for_module' => 'Subscription', 'icon' => '#2323232', 'text_color' => 'green','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'inactive', 'for_module' => 'Subscription', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'gst', 'for_module' => 'inv_tex_type', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'pan', 'for_module' => 'inv_tex_type', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'tan', 'for_module' => 'inv_tex_type', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'unpaid', 'for_module' => 'invoice_payment', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'partial paid', 'for_module' => 'invoice_payment', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'paid', 'for_module' => 'invoice_payment', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            ['status' => 'refuse', 'for_module' => 'invoice_payment', 'icon' => '#2323232', 'text_color' => 'red','created_at'=>now(),'updated_at'=>now()],
            // Add more statuses as needed
        ];

        // Insert the statuses into the database
        DB::table('statuses')->insert($status);
    }
}
