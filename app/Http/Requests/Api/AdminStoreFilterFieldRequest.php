<?php

namespace App\Http\Requests\Api;

use App\Rules\FilterFieldKey;
use App\Rules\FilterFieldName;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreFilterFieldRequest extends FormRequest
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
            'name'                  => ['required', 'string', new FilterFieldName()],
            'key'                   => ['required', 'string', new FilterFieldKey()],
            'factory_system_id'     => ['required', 'exists:factory_systems,id'],
            'default'               => ['required', 'boolean'],
            'default_value'         => ['required'],
            'default_type_id'       => ['required', 'integer', 'exists:filter_types,id'],
            'default_operator_id'   => ['required', 'integer', 'exists:filter_operators,id'],
        ];
    }
}
