<?php

namespace Modules\ClientManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ClientManagement\Models\Order;
use Modules\ClientManagement\Models\OrderItem;
use Modules\ClientManagement\Http\Requests\StoreOrderItemRequest;
use Modules\ClientManagement\Http\Requests\UpdateOrderItemRequest;
use Illuminate\Http\JsonResponse;

class OrderItemController extends Controller
{
    /**
     * Get all items for a given order.
     */
    public function index(int $orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);
        $items = $order->items()->with(['website', 'vendor'])->latest()->get();

        return response()->json([
            'status' => 'success',
            'order' => $order,
            'data' => $items,
        ]);
    }

    /**
     * Store a newly created order item and recalculate order totals.
     */
    public function store(StoreOrderItemRequest $request, int $orderId): JsonResponse
    {
        $order = Order::findOrFail($orderId);
        $validated = $request->validated();
        $validated['order_id'] = $order->id;

        $item = OrderItem::create($validated);

        // Recalculate order totals
        $order->recalculateTotals();

        return response()->json([
            'status' => 'success',
            'message' => 'Guest post item added to order successfully!',
            'data' => $item->load(['website', 'vendor']),
            'order' => $order->fresh(),
        ], 201);
    }

    /**
     * Show a specific item.
     */
    public function show(int $id): JsonResponse
    {
        $item = OrderItem::with(['order', 'website', 'vendor'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ]);
    }

    /**
     * Update an order item and recalculate totals.
     */
    public function update(UpdateOrderItemRequest $request, int $id): JsonResponse
    {
        $item = OrderItem::findOrFail($id);
        $item->update($request->validated());

        // Recalculate parent order totals
        $order = $item->order;
        if ($order) {
            $order->recalculateTotals();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order item updated successfully!',
            'data' => $item->fresh(['website', 'vendor']),
            'order' => $order ? $order->fresh() : null,
        ]);
    }

    /**
     * Delete an order item and recalculate totals.
     */
    public function destroy(int $id): JsonResponse
    {
        $item = OrderItem::findOrFail($id);
        $order = $item->order;
        $item->delete();

        if ($order) {
            $order->recalculateTotals();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order item deleted successfully!',
            'order' => $order ? $order->fresh() : null,
        ]);
    }
}
