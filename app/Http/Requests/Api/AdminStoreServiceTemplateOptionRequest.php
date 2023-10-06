<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreServiceTemplateOptionRequest extends FormRequest
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
            'service_option_id' => ['required', 'integer', 'exists:service_options,id'],
            'target'            => ['required', 'string', 'in:source,destination'],
            'value'             => ['required'],
            'user_configurable' => ['filled', 'boolean'],
        ];
    }
}
