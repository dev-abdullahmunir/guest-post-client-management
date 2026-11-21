<?php

namespace Modules\ClientManagement\Http\Requests;

use App\Http\Requests\FormRequest;

class UpdateOrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website_id' => 'sometimes|required|exists:websites,id',
            'vendor_id' => 'sometimes|required|exists:sellers,id',
            'vendor_price' => 'sometimes|required|numeric|min:0',
            'client_price' => 'sometimes|required|numeric|min:0',
            'anchor_text' => 'sometimes|required|string|max:255',
            'target_url' => 'sometimes|required|url|max:255',
            'anchor_deadline' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,published,completed,cancelled',
        ];
    }
}
