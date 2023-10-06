<?php

namespace App\Http\Requests\Api;

use App\Rules\Cron;
use App\Rules\Timezone;
use App\Models\Fabric\Entity;
use App\Rules\UserCanSetAdminAttribute;
use App\Rules\Subscription\MaximumActiveServices;

class UpdateShipmentMapRequest extends StoreServiceRequest
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
            'methods'  => ['filled', 'array'],
            'fallback' => ['filled', 'string', 'min:3', 'max:255'],
        ];
    }
}
