<?php

namespace Modules\ClientManagement\Http\Requests;

use App\Http\Requests\FormRequest;

class StoreOrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'website_id' => 'required|exists:websites,id',
            'vendor_id' => 'required|exists:sellers,id',
            'vendor_price' => 'required|numeric|min:0',
            'client_price' => 'required|numeric|min:0',
            'anchor_text' => 'required|string|max:255',
            'target_url' => 'required|url|max:255',
            'anchor_deadline' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,published,completed,cancelled',
        ];
    }
}
