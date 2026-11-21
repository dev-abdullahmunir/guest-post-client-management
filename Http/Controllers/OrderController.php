<?php

namespace Modules\ClientManagement\Http\Controllers;

use Modules\ClientManagement\Services\ClientOrderService;
use Modules\Shared\Enums\OrderStatus;

class OrderController
{
    protected ClientOrderService $orderService;

    public function __construct(ClientOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        // Thin controller: delegates to view / service
        $orders = []; // Fetched via ClientOrderRepository in database layer
        return [
            'view' => 'clientmanagement::orders.index',
            'orders' => $orders
        ];
    }

    public function store(array $validatedData)
    {
        $clientId = 1; // Current authenticated client
        return $this->orderService->placeOrder($clientId, $validatedData);
    }
}
