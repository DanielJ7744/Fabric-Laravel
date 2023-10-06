<?php

namespace App\Http\Requests\Api;

use App\Rules\FactorySystem\DisplayName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateFactorySystemRequest extends FormRequest
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
            'direction'         => ['filled', 'string', 'in:pull,push', Rule::unique('factory_systems')->ignore($this->factory_system->id)->where(function ($query) {
                return $query->where('factory_id', $this->factory_id)->where('system_id', $this->system_id);
            })],
            'factory_id'        => ['filled', 'integer', 'exists:factories,id'],
            'system_id'         => ['filled', 'integer', 'exists:systems,id'],
            'entity_id'         => ['filled', 'integer', 'exists:entities,id'],
            'default_map_name'  => ['nullable', 'string'],
            'integration_id'    => ['nullable', 'integer', 'exists:integrations,id'],
            'display_name'      => ['filled', 'string', 'min:3', 'max:255', new DisplayName()],
        ];
    }
}
