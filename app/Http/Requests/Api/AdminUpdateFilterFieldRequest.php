<?php

namespace App\Http\Requests\Api;

use App\Rules\FilterFieldKey;
use App\Rules\FilterFieldName;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateFilterFieldRequest extends FormRequest
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
            'name'                  => ['filled', 'string', new FilterFieldName()],
            'key'                   => ['filled', 'string', new FilterFieldKey()],
            'factory_system_id'     => ['filled', 'exists:factory_systems,id'],
            'default'               => ['filled', 'boolean'],
            'default_value'         => ['filled'],
            'default_type_id'       => ['filled', 'integer', 'exists:filter_types,id'],
            'default_operator_id'   => ['filled', 'integer', 'exists:filter_operators,id'],
        ];
    }
}
