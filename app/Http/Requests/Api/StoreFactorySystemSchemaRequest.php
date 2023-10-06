<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreFactorySystemSchemaRequest extends FormRequest
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
            'type'                  => ['required', 'string', 'min:3', 'max:30'],
            'schema'                => ['required', 'string', 'min:1', 'max:2147483647'],
            'integration_id'        => ['required', 'integer', 'exists:integrations,id'],
            'factory_system_id'     => ['required', 'integer', 'exists:factory_systems,id'],
            'original_type'         => ['nullable', 'string', 'min:3', 'max:30'],
            'original_schema'       => ['nullable', 'string', 'min:1', 'max:2147483647'],
        ];
    }
}
