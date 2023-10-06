<?php

namespace App\Http\Requests\Api;

use App\Rules\UserCanSetAdminAttribute;
use Illuminate\Foundation\Http\FormRequest;

class StoreMappingRequest extends FormRequest
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
            'integration_id'    => ['required_without:username_override', 'integer', 'exists:integrations,id'],
            'mapping_name'      => ['required', 'string', 'min:3', 'max:500'],
            'content'           => ['required', 'json'],
            'search_field'      => ['filled', 'string', 'min:3', 'max:500'],
            'username_override' => ['required_without:integration_id', 'string', 'min:3', 'max:255', new UserCanSetAdminAttribute()],
        ];
    }
}
