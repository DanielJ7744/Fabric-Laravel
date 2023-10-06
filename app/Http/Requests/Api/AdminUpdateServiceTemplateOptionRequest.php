<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateServiceTemplateOptionRequest extends FormRequest
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
            'service_option_id' => ['filled', 'integer', 'exists:service_options,id'],
            'target'            => ['filled', 'string', 'in:source,destination'],
            'value'             => ['filled'],
            'user_configurable' => ['filled', 'boolean'],
        ];
    }
}
