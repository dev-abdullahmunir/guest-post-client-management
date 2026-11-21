<?php

namespace Modules\ClientManagement\Http\Requests;

use App\Http\Requests\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'order_date' => 'required|date',
            'deadline' => 'nullable|date',
            'total_client_price' => 'required|numeric|min:0',
            'total_vendor_cost' => 'required|numeric|min:0',
            'status' => 'nullable|in:pending,in_progress,published,completed,cancelled',
            'notes' => 'nullable|string',
        ];
    }
}
