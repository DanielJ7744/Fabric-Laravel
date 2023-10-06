<?php

namespace App\Http\Requests\Api;

use App\Models\Fabric\Company;
use App\Rules\IntegrationName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminUpdateIntegrationRequest extends FormRequest
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
                        ->whereCompanyId($this->integration->company_id)
                        ->whereName($this->name)
                        ->where('id', '!=', $this->integration->id);
                }),
            ],
            'server'   => ['filled', 'string', 'min:3', 'max:255'],
            'active'   => ['filled', 'boolean'],
            'parent_id' => ['filled', 'integer', 'exists:integrations,id']
        ];
    }

    /**
     * Prepare incoming data for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'server' => sprintf('%s.pwks.co', Str::slug(str_replace('.pwks.co', '', $this->input('server'))))
        ]);
    }
}
