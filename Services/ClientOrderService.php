<?php

namespace Modules\ClientManagement\Services;

use Modules\Shared\Services\BaseService;
use Modules\Shared\Enums\OrderStatus;

class ClientOrderService extends BaseService
{
    /**
     * Create a new guest post order
     */
    public function placeOrder(int $clientId, array $data)
    {
        // 1. Validation logic for client wallet / balance
        // 2. Prepare payload
        $orderData = [
            'client_id' => $clientId,
            'website_id' => $data['website_id'],
            'anchor_text' => $data['anchor_text'],
            'target_url' => $data['target_url'],
            'content_type' => $data['content_type'] ?? 'uploaded',
            'special_instructions' => $data['special_instructions'] ?? null,
            'status' => OrderStatus::PENDING,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // 3. Return response
        return $this->success($orderData, 'Guest post order successfully created.');
    }

    /**
     * Request a revision for an order
     */
    public function requestRevision(int $orderId, string $notes)
    {
        return $this->success([
            'order_id' => $orderId,
            'status' => OrderStatus::REVISION_REQUESTED,
            'revision_notes' => $notes,
        ], 'Revision requested successfully.');
    }
}
