<?php

namespace App\Http\Requests\Api;

use App\Rules\CompanyName;
use App\Rules\PhoneNumberFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateCompanyRequest extends FormRequest
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
            'name'              => ['filled', 'string', 'min:3', 'max:128', Rule::unique('companies')->ignore($this->company->id), new CompanyName()],
            'active'            => ['filled', 'boolean'],
            'trial_ends_at'     => ['nullable', 'date_format:Y-m-d H:i:s'],
            'company_website'   => ['nullable', 'string', 'url'],
            'company_phone'     => ['nullable', 'min:8', 'max:18', new PhoneNumberFormat],
            'company_email'     => ['nullable', 'email'],
        ];
    }
}
