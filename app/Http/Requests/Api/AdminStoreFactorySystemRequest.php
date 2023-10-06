<?php

namespace App\Http\Requests\Api;

use App\Rules\FactorySystem\DisplayName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminStoreFactorySystemRequest extends FormRequest
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
            'direction'         => ['required', 'string', 'in:pull,push', Rule::unique('factory_systems')->where(function ($query) {
                return $query->where('factory_id', $this->factory_id)->where('system_id', $this->system_id)->where('entity_id', $this->entity_id);
            })],
            'factory_id'        => ['required', 'integer', 'exists:factories,id'],
            'system_id'         => ['required', 'integer', 'exists:systems,id'],
            'entity_id'         => ['required', 'integer', 'exists:entities,id'],
            'default_map_name'  => ['nullable', 'string'],
            'integration_id'    => ['nullable', 'integer', 'exists:integrations,id'],
            'display_name'      => ['required', 'string', 'min:3', 'max:255', new DisplayName()],
        ];
    }
}
