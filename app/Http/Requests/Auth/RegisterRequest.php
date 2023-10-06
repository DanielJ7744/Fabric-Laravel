<?php

namespace App\Http\Requests\Auth;

use App\Models\Fabric\Integration;
use App\Rules\CompanyName;
use App\Rules\PhoneNumberFormat;
use App\Rules\SystemWebsite;
use App\Rules\UserPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'terms' => 'accepted',
            'social_token' => ['nullable', 'required_without:user.password', function ($attribute, $value, $fail) {
                if (Cache::missing($value)) {
                    $fail('Social token is invalid, please try again');
                }
            }],

            'user' => ['array'],
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'user.password' => ['nullable', 'required_without:social_token', 'string', 'min:8', 'confirmed', new UserPassword],

            'company' => ['array'],
            'company.name' => ['required', 'string', 'min:3', 'max:128', 'unique:companies,name', new CompanyName, function ($attribute, $value, $fail) {
                if (Integration::whereUsername(Str::slug($value, '_'))->exists()) {
                    $fail('Company name is already taken.');
                }
            }],
            'company.company_email'   => ['required', 'string', 'email', 'max:255'],
            'company.company_phone'   => ['nullable', 'min:8', 'max:18', new PhoneNumberFormat],
            'company.company_website' => ['nullable', 'string', 'min:3', 'max:255', new SystemWebsite],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'user.name' => 'name',
            'user.email' => 'email address',
            'user.password' => 'password',
            'company.name' => 'company name',
            'company.company_email' => 'company email address',
            'company.company_phone' => 'company phone number',
            'company.company_website' => 'company website url',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'terms.accepted' => 'Please indicate that you have read and agree to the terms and conditions.',
            'social_token.required_without' => 'A social account was not provided, please try again or register with a password instead.',
            'user.name.required' => 'Your name is required.',
            'user.email.required' => 'Your email is required.',
            'user.password.required_without' => 'A password is required unless registering with a social account.',
            'company.name.required' => 'A company name is required.',
            'company.company_email.required' => 'A company email is required.',
        ];
    }
}
