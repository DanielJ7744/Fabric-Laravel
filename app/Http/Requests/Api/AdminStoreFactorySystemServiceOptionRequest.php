<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreFactorySystemServiceOptionRequest extends FormRequest
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
            'value'             => ['required'],
            'user_configurable' => ['filled', 'boolean'],
        ];
    }
}
