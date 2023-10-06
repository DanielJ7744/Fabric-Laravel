<?php

namespace App\Http\Requests\Api;

use App\Rules\IntegrationHasService;
use App\Rules\ServiceSourceHasEventType;
use App\Rules\Webhook;
use Illuminate\Foundation\Http\FormRequest;

class StoreWebhookRequest extends FormRequest
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
            'service_id' => [
                'required',
                new IntegrationHasService()
            ],
            'event_type_id' => ['required', 'exists:event_types,id', new ServiceSourceHasEventType($this->input('service_id'))],
            'payload' => ['array', new Webhook($this->input('service_id'))],
        ];
    }
}
