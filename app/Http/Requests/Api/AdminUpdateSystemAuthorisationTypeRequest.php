<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateSystemAuthorisationTypeRequest extends FormRequest
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
            'system_id'             => ['filled', 'integer', 'exists:systems,id'],
            'authorisation_type_id' => ['filled', 'integer', 'exists:authorisation_types,id'],
            'credentials_schema'    => ['filled', 'json'],
        ];
    }
}
