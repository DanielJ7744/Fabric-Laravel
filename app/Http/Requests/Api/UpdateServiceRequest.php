<?php

namespace App\Http\Requests\Api;

use App\Rules\Cron;
use App\Rules\Timezone;
use App\Models\Fabric\Entity;
use App\Rules\UserCanSetAdminAttribute;
use App\Rules\Subscription\MaximumActiveServices;

class UpdateServiceRequest extends StoreServiceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->service);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'status'       => ['filled', 'boolean', new MaximumActiveServices],
            'description'  => ['filled', 'string', 'min:3', 'max:255'],
            'schedule'     => ['filled', 'string', 'max:50', new Cron],

            'from_options'               => ['filled'],
            'from_options.timezone'      => ['filled', 'string', new Timezone],
            'from_options.page_size'     => ['filled', 'integer', 'min:1', 'max:1000'],
            'from_options.max_attempts'  => ['filled', 'integer', 'min:1', 'max:15'],
            'from_options.filters'       => ['sometimes', 'array'],

            'to_options'              => ['nullable'],
            'to_options.timezone'     => ['filled', 'string', new Timezone],

            'from_mapping'  => ['nullable', 'string'],
            'to_mapping'    => ['nullable', 'string'],

            'billable' => ['filled', 'boolean', new UserCanSetAdminAttribute]
        ];
    }
}
