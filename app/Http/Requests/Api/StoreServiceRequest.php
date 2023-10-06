<?php

namespace App\Http\Requests\Api;

use App\Rules\Cron;
use App\Models\Tapestry\Service;
use App\Rules\UserCanSetAdminAttribute;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Service::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description'            => ['required', 'string', 'min:3', 'max:255'],
            'schedule'               => ['required', 'string', 'max:50', new Cron],
            'from_environment'       => ['required', 'string', 'min:3'],
            'to_environment'         => ['required', 'string', 'min:3'],

            'from_options'               => ['filled'],
            'from_options.page_size'     => ['required', 'integer', 'min:1', 'max:1000'],
            'from_options.max_attempts'  => ['required', 'integer', 'min:1', 'max:15'],

            'to_options'                 => ['filled'],

            'service_template_id'        => ['required', 'bail', 'exists:service_templates,id'],

            'billable' => ['filled', 'boolean', new UserCanSetAdminAttribute],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'description' => 'service name',

            'from_options.page_size'        => 'maximum page size',
            'from_options.max_attempts'     => 'maximum attempts',
            'from_options.calls_per_second' => 'calls per second',

            'to_options.page_size'    => 'maximum page size',
            'to_options.max_attempts' => 'maximum attempts',
        ];
    }
}
