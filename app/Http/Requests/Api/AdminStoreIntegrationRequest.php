<?php

namespace App\Http\Requests\Api;

use Illuminate\Auth\Access\Response;
use function app;
use Illuminate\Contracts\Auth\Access\Gate;
use App\Rules\IntegrationName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminStoreIntegrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return Response
     */
    public function authorize(): Response
    {
        return app(Gate::class)->authorize('create admin-integration');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'name'       => [
                'required', 'string', 'min:3', 'max:128', new IntegrationName(),
                Rule::unique('integrations')->where(function ($query) {
                    return $query
                        ->whereCompanyId($this->company_id)
                        ->whereName($this->name);
                }),
            ],
            'username'   => ['required', 'string', 'min:3', 'max:255'],
            'active'     => ['filled', 'boolean'],
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
            'username' => Str::slug($this->username, '_'),
        ]);
    }
}
