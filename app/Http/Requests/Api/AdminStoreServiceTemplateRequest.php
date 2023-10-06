<?php

namespace App\Http\Requests\Api;

use App\Rules\FilterTemplates\Name;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminStoreServiceTemplateRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:255', new Name()],
            'source_factory_system_id' => ['required', 'integer', 'exists:factory_systems,id', Rule::unique('service_templates')->where(function ($query) {
                return $query->where('destination_factory_system_id', $this->destination_factory_system_id);
            })],
            'destination_factory_system_id' => ['required', 'integer', 'exists:factory_systems,id', Rule::unique('service_templates')->where(function ($query) {
                return $query->where('source_factory_system_id', $this->source_factory_system_id);
            })],
            'integration_id' => ['nullable', 'integer', 'exists:integrations,id', Rule::unique('service_templates')->where(function ($query) {
                return $query->where('source_factory_system_id', $this->source_factory_system_id)->where('destination_factory_system_id', $this->destination_factory_system_id);
            })],
            'enabled' => ['filled', 'boolean']
        ];
    }
}
