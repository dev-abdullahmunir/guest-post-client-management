<?php

namespace Modules\ClientManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shared\Models\Order;
use Modules\Shared\Models\Client;
use Modules\Shared\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * Render the Orders Blade view.
     */
    public function renderView($viewFactory)
    {
        try {
            $orders = Order::with(['client', 'items.website', 'items.vendor'])->latest('order_date')->get()->toArray();
            $clients = Client::orderBy('name')->get()->toArray();
            $websites = Website::with('vendor')->where('status', 'active')->orderBy('website_name')->get()->toArray();
        } catch (\Exception $e) {
            $orders = [];
            $clients = [];
            $websites = [];
        }

        return $viewFactory->make('orders.index', [
            'orders' => $orders,
            'clients' => $clients,
            'websites' => $websites,
        ]);
    }

    /**
     * Display a listing of orders (API / JSON).
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::with('client')->latest('order_date')->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created order.
     */
    public function store(array $body): array
    {
        if (empty($body['client_id']) || empty($body['order_date'])) {
            http_response_code(422);
            return ['status' => 'error', 'message' => 'Client and Order Date are required.'];
        }

        $clientPrice = (float)($body['total_client_price'] ?? 0);
        $vendorCost = (float)($body['total_vendor_cost'] ?? 0);
        $totalProfit = $clientPrice - $vendorCost;

        $orderNumber = 'ORD-' . strtoupper(substr(uniqid(), -6));

        $order = Order::create([
            'order_number' => $orderNumber,
            'client_id' => (int)$body['client_id'],
            'order_date' => $body['order_date'],
            'deadline' => !empty($body['deadline']) ? $body['deadline'] : null,
            'total_client_price' => $clientPrice,
            'total_vendor_cost' => $vendorCost,
            'total_profit' => $totalProfit,
            'status' => $body['status'] ?? 'pending',
            'notes' => trim($body['notes'] ?? null) ?: null,
        ]);

        return [
            'status' => 'success',
            'message' => 'Order created successfully!',
            'data' => $order->load('client')
        ];
    }

    /**
     * Display the specified order.
     */
    public function show($id): array
    {
        $order = Order::with(['client', 'items.website', 'items.vendor'])->findOrFail($id);
        return [
            'status' => 'success',
            'data' => $order
        ];
    }

    /**
     * Update the specified order.
     */
    public function update($id, array $body): array
    {
        $order = Order::findOrFail($id);

        $clientPrice = isset($body['total_client_price']) ? (float)$body['total_client_price'] : $order->total_client_price;
        $vendorCost = isset($body['total_vendor_cost']) ? (float)$body['total_vendor_cost'] : $order->total_vendor_cost;
        $totalProfit = $clientPrice - $vendorCost;

        $order->update([
            'client_id' => !empty($body['client_id']) ? (int)$body['client_id'] : $order->client_id,
            'order_date' => !empty($body['order_date']) ? $body['order_date'] : $order->order_date,
            'deadline' => array_key_exists('deadline', $body) ? ($body['deadline'] ?: null) : $order->deadline,
            'total_client_price' => $clientPrice,
            'total_vendor_cost' => $vendorCost,
            'total_profit' => $totalProfit,
            'status' => $body['status'] ?? $order->status,
            'notes' => array_key_exists('notes', $body) ? (trim($body['notes'] ?? null) ?: null) : $order->notes,
        ]);

        return [
            'status' => 'success',
            'message' => 'Order updated successfully!',
            'data' => $order->fresh()->load('client')
        ];
    }

    /**
     * Remove the specified order.
     */
    public function destroy($id): array
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return [
            'status' => 'success',
            'message' => 'Order deleted successfully.'
        ];
    }
}
