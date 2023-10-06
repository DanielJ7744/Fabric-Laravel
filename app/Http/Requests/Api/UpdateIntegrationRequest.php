<?php

namespace App\Http\Requests\Api;

use App\Models\Fabric\Company;
use App\Rules\IntegrationName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateIntegrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'name'     => [
                'filled', 'string', 'min:3', 'max:128',  new IntegrationName(),
                Rule::unique('integrations')->where(function ($query) {
                    return $query
                        ->whereCompanyId(Company::current()->getKey())
                        ->whereName($this->name);
                }),
            ],
            'active'   => ['filled', 'boolean'],
            'parent_id'  => ['filled', 'integer', 'exists:integrations,id'],
        ];
    }
}
