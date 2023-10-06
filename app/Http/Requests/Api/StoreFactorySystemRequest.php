<?php

namespace App\Http\Requests\Api;

use App\Models\Tapestry\Connector;
use App\Rules\FactorySystem\DisplayName;
use Illuminate\Foundation\Http\FormRequest;

class StoreFactorySystemRequest extends FormRequest
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
            'direction'         => ['required', 'string', 'in:pull,push'],
            'factory_id'        => ['required', 'integer', 'exists:factories,id'],
            'system_id'         => ['required', 'integer', 'exists:systems,id'],
            'entity_id'         => ['required', 'integer', 'exists:entities,id'],
            'integration_id'    => ['required', 'integer', 'exists:integrations,id'],
            'display_name'      => ['required', 'string', 'min:3', 'max:255', new DisplayName()],
        ];
    }
}
