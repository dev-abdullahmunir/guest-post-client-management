<?php

namespace Modules\ClientManagement\Http\Requests;

use App\Http\Requests\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ];
    }
}
