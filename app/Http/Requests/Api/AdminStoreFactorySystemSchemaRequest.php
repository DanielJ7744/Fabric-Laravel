<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreFactorySystemSchemaRequest extends FormRequest
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
            'integration_id'        => ['nullable', 'integer', 'exists:integrations,id'],
            'factory_system_id'     => ['required', 'integer', 'exists:factory_systems,id'],
            'type'                  => ['required', 'string', 'min:3', 'max:30'],
            'schema'                => ['required', 'string', 'min:1', 'max:2147483647'],
        ];
    }
}
