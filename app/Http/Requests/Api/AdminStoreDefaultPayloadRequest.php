<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreDefaultPayloadRequest extends FormRequest
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
            'factory_system_schema_id' => ['required', 'integer', 'exists:factory_system_schemas,id', 'unique:default_payloads,factory_system_schema_id'],
            'type'                     => ['required', 'string', 'min:3', 'max:24'],
            'payload'                  => ['required', 'string', 'min:3', 'max:2147483647'],
        ];
    }
}
