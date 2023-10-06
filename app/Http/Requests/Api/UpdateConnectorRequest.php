<?php

namespace App\Http\Requests\Api;

use App\Rules\Credentials;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConnectorRequest extends FormRequest
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
            'credentials'   => [
                'sometimes',
                'filled',
                'array',
                new Credentials($this->route('connector')->system->id, true)
            ],
            'environment'        => ['required', 'string'],
            'authorisation_type' => ['filled', 'string'],
            'connectorName'      => ['filled', 'string'],
            'timeZone'           => ['filled', 'string'],
            'dateFormat'         => ['filled', 'string'],
        ];
    }
}
