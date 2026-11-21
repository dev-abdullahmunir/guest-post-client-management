<?php

namespace Modules\ClientManagement\Http\Requests;

use App\Http\Requests\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'sometimes|required|exists:clients,id',
            'order_date' => 'sometimes|required|date',
            'deadline' => 'nullable|date',
            'total_client_price' => 'sometimes|required|numeric|min:0',
            'total_vendor_cost' => 'sometimes|required|numeric|min:0',
            'status' => 'nullable|in:pending,in_progress,published,completed,cancelled',
            'notes' => 'nullable|string',
        ];
    }
}
