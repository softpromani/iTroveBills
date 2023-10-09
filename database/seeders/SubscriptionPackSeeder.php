<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptionpacks = [
            ['title' => 'Free Plan', 'slug' => 'free-plan', 'short_desc' => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Assumenda, libero.','month'=>12,'status' => Status::moduleStatusId('Subscription','active'),'created_at' => now(),'updated_at' => now()],
            // Add more roles as needed
        ];

        // Insert the roles into the database
        DB::table('subscription_packs')->insert($subscriptionpacks);
    }
}
