<?php

namespace App\Http\Requests\Api;

use App\Rules\PhoneNumberFormat;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreUserRequest extends FormRequest
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
            'name'      => ['required', 'string', 'min:3', 'max:46'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'telephone'  => ['nullable', 'min:8', 'max:18', new PhoneNumberFormat],
        ];
    }
}
