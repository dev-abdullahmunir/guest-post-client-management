<?php

namespace Modules\ClientManagement\Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->insertOrIgnore([
            [
                'id' => 1,
                'order_number' => 'ORD-2026-001',
                'client_id' => 1,
                'order_date' => date('Y-m-d', strtotime('-5 days')),
                'deadline' => date('Y-m-d', strtotime('+3 days')),
                'total_client_price' => 150.00,
                'total_vendor_cost' => 75.00,
                'total_profit' => 75.00,
                'status' => 'in_progress',
                'notes' => 'Target URL: growth.co/ai-tools, Anchor: Best AI SEO Software. Publishing on Tech blog.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'order_number' => 'ORD-2026-002',
                'client_id' => 2,
                'order_date' => date('Y-m-d', strtotime('-8 days')),
                'deadline' => date('Y-m-d', strtotime('-2 days')),
                'total_client_price' => 380.00,
                'total_vendor_cost' => 180.00,
                'total_profit' => 200.00,
                'status' => 'published',
                'notes' => 'Live Link delivered. Client approved anchor text placement on UK Finance Review.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'order_number' => 'ORD-2026-003',
                'client_id' => 3,
                'order_date' => date('Y-m-d', strtotime('-1 day')),
                'deadline' => date('Y-m-d', strtotime('+5 days')),
                'total_client_price' => 550.00,
                'total_vendor_cost' => 280.00,
                'total_profit' => 270.00,
                'status' => 'pending',
                'notes' => 'VentureBeat contributor slot. Draft in editorial review.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
