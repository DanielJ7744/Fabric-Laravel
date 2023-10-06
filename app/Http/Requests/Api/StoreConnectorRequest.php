<?php

namespace App\Http\Requests\Api;

use App\Rules\Credentials;
use App\Rules\CommonRefUnique;
use App\Rules\Subscription\SubscriptionHasBusinessInsights;
use Illuminate\Foundation\Http\FormRequest;

class StoreConnectorRequest extends FormRequest
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
            'credentials'       => ['required', 'array', new Credentials($this->input('system_id'))],
            'environment'       => [
                'required',
                'string',
                'not_regex:/^(.*)_(live|dev|test|stag|prod)$/i',
                new CommonRefUnique($this->input('integration_id'), $this->input('system_id'))
            ],
            'connectorName'      => ['required', 'string'],
            'timeZone'           => ['required', 'string'],
            'dateFormat'         => ['required', 'string'],
            'authorisation_type' => ['required', 'string'],
            'system_id'          => ['required', 'exists:systems,id', new SubscriptionHasBusinessInsights($this->input('system_id'))],
            'integration_id'     => ['required', 'exists:integrations,id'],
        ];
    }
}
