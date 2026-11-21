<?php

namespace Modules\ClientManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ClientManagement\Models\Order;
use Modules\ClientManagement\Http\Requests\StoreOrderRequest;
use Modules\ClientManagement\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of guest post orders.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with('client');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                  });
        }

        if ($request->has('status') && !empty($request->get('status'))) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('client_id')) {
            $query->where('client_id', $request->get('client_id'));
        }

        $orders = $query->latest('order_date')->paginate(15);

        return Response::json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created order.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $clientPrice = (float)$data['total_client_price'];
        $vendorCost = (float)$data['total_vendor_cost'];
        $data['total_profit'] = $clientPrice - $vendorCost;

        $order = Order::create($data);

        return Response::json([
            'status' => 'success',
            'message' => 'Order created successfully.',
            'data' => $order->load('client')
        ], 201);
    }

    /**
     * Display the specified order.
     */
    public function show($id): JsonResponse
    {
        $order = Order::with('client')->findOrFail($id);

        return Response::json([
            'status' => 'success',
            'data' => $order
        ]);
    }

    /**
     * Update the specified order.
     */
    public function update(UpdateOrderRequest $request, $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        $data = $request->validated();

        if (isset($data['total_client_price']) || isset($data['total_vendor_cost'])) {
            $clientPrice = (float)($data['total_client_price'] ?? $order->total_client_price);
            $vendorCost = (float)($data['total_vendor_cost'] ?? $order->total_vendor_cost);
            $data['total_profit'] = $clientPrice - $vendorCost;
        }

        $order->update($data);

        return Response::json([
            'status' => 'success',
            'message' => 'Order updated successfully.',
            'data' => $order->load('client')
        ]);
    }

    /**
     * Remove the specified order (Soft delete).
     */
    public function destroy($id): JsonResponse
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Order soft-deleted successfully.'
        ]);
    }
}
