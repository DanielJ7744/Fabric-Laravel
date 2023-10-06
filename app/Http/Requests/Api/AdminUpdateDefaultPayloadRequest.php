<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateDefaultPayloadRequest extends FormRequest
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
            'factory_system_schema_id' => ['filled', 'integer', 'exists:factory_system_schemas,id', Rule::unique('default_payloads')->ignore($this->default_payload->id)],
            'type'                     => ['filled', 'string', 'min:3', 'max:24'],
            'payload'                  => ['filled', 'string', 'min:3', 'max:2147483647'],
        ];
    }
}
