<?php

namespace App\Http\Requests\Api;

use App\Rules\UserPassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMyPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => 'required',
            'password' => ['required', 'confirmed', 'string', 'min:8', new UserPassword()]
        ];
    }
}
