<?php

namespace Modules\ClientManagement\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clients')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Sarah Khan',
                'email' => 'sarah@growth.co',
                'phone' => '+14155552671',
                'company_name' => 'SaaS Growth Agency',
                'country' => 'United States',
                'notes' => 'VIP Buyer - Focused on Tech & AI guest posts.',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Ali Raza',
                'email' => 'ali@fintech.io',
                'phone' => '+447911123456',
                'company_name' => 'Fintech Flow',
                'country' => 'United Kingdom',
                'notes' => 'Requires high DR finance & crypto blogs.',
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Emma Davis',
                'email' => 'emma@digitalmedia.co',
                'phone' => '+13125559812',
                'company_name' => 'Digital Media Ltd',
                'country' => 'United States',
                'notes' => 'Monthly retainer client (5 posts / month).',
                'created_at' => now(),
            ],
        ]);
    }
}
