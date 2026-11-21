<?php

namespace Modules\ClientManagement\Database\seeders;

use Illuminate\Database\Capsule\Manager as Capsule;

class OrderItemDatabaseSeeder
{
    /**
     * Run the database seeds for order_items.
     */
    public function run(): void
    {
        $orders = Capsule::table('orders')->get();
        $websites = Capsule::table('websites')->get();

        if ($orders->isEmpty() || $websites->isEmpty()) {
            return;
        }

        Capsule::table('order_items')->truncate();

        $items = [
            [
                'order_index' => 0,
                'website_index' => 0, // TechCrunch
                'anchor_text' => 'best saas accounting tool',
                'target_url' => 'https://acme.com/saas-tools',
                'client_price' => 250.00,
                'vendor_price' => 180.00,
                'anchor_deadline' => date('Y-m-d', strtotime('+7 days')),
                'status' => 'in_progress',
            ],
            [
                'order_index' => 0,
                'website_index' => 1, // Forbes
                'anchor_text' => 'enterprise cloud architecture',
                'target_url' => 'https://acme.com/cloud-solutions',
                'client_price' => 600.00,
                'vendor_price' => 450.00,
                'anchor_deadline' => date('Y-m-d', strtotime('+10 days')),
                'status' => 'pending',
            ],
            [
                'order_index' => 1,
                'website_index' => 2, // Healthline
                'anchor_text' => 'natural wellness routines',
                'target_url' => 'https://nexus.org/wellness',
                'client_price' => 220.00,
                'vendor_price' => 150.00,
                'anchor_deadline' => date('Y-m-d', strtotime('+5 days')),
                'status' => 'published',
            ],
            [
                'order_index' => 1,
                'website_index' => 3, // Search Engine Journal
                'anchor_text' => 'seo ranking strategies',
                'target_url' => 'https://nexus.org/seo-guide',
                'client_price' => 280.00,
                'vendor_price' => 180.00,
                'anchor_deadline' => date('Y-m-d', strtotime('+12 days')),
                'status' => 'in_progress',
            ],
            [
                'order_index' => 2,
                'website_index' => 1, // Forbes
                'anchor_text' => 'fintech disruption in banking',
                'target_url' => 'https://quantummedia.io/fintech',
                'client_price' => 650.00,
                'vendor_price' => 450.00,
                'anchor_deadline' => date('Y-m-d', strtotime('+14 days')),
                'status' => 'pending',
            ],
            [
                'order_index' => 3,
                'website_index' => 4, // Investopedia
                'anchor_text' => 'wealth management tips',
                'target_url' => 'https://zenithcorp.co/wealth',
                'client_price' => 290.00,
                'vendor_price' => 200.00,
                'anchor_deadline' => date('Y-m-d', strtotime('-2 days')),
                'status' => 'completed',
            ],
        ];

        $now = date('Y-m-d H:i:s');

        foreach ($items as $item) {
            $order = $orders->get($item['order_index']) ?? $orders->first();
            $website = $websites->get($item['website_index']) ?? $websites->first();

            Capsule::table('order_items')->insert([
                'order_id' => $order->id,
                'website_id' => $website->id,
                'vendor_id' => $website->vendor_id,
                'vendor_price' => $item['vendor_price'],
                'client_price' => $item['client_price'],
                'anchor_text' => $item['anchor_text'],
                'target_url' => $item['target_url'],
                'anchor_deadline' => $item['anchor_deadline'],
                'status' => $item['status'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Recalculate totals for all orders that have items
        $distinctOrderIds = Capsule::table('order_items')->distinct()->pluck('order_id');
        foreach ($distinctOrderIds as $orderId) {
            $clientTotal = Capsule::table('order_items')->where('order_id', $orderId)->sum('client_price');
            $vendorTotal = Capsule::table('order_items')->where('order_id', $orderId)->sum('vendor_price');
            $profit = $clientTotal - $vendorTotal;

            Capsule::table('orders')->where('id', $orderId)->update([
                'total_client_price' => $clientTotal,
                'total_vendor_cost' => $vendorTotal,
                'total_profit' => $profit,
                'updated_at' => $now,
            ]);
        }
    }
}
