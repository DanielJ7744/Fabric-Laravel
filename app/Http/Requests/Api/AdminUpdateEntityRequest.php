<?php

namespace App\Http\Requests\Api;

use App\Models\Tapestry\Connector;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateEntityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'              => ['filled', 'string', 'min:3', 'max:255'],
            'integration_id'    => ['nullable', 'integer', 'exists:integrations,id']
        ];
    }
}
