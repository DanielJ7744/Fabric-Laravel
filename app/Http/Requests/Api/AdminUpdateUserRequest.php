<?php

namespace App\Http\Requests\Api;

use App\Rules\PhoneNumberFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateUserRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'       => ['filled', 'string', 'min:3', 'max:46'],
            'email'      => ['filled', 'email', Rule::unique('users')->ignore($this->user->id)],
            'telephone'  => ['nullable', 'min:8', 'max:18', new PhoneNumberFormat],
            'company_id' => ['nullable', 'integer', 'exists:companies,id']
        ];
    }
}
