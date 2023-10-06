<?php

namespace App\Http\Requests\Api;

use App\Rules\FilterTemplates\FilterKey;
use App\Rules\FilterTemplates\Name;
use App\Rules\FilterTemplates\PWValueField;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateFilterTemplateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'              => ['filled', 'string', new Name()],
            'filter_key'        => ['filled', 'string', new FilterKey()],
            'factory_system_id' => ['filled', 'exists:factory_systems,id'],
            'template'          => ['filled', 'json'],
            'note'              => ['string', 'nullable'],
            'pw_value_field'    => ['filled', 'string', new PWValueField()],
        ];
    }
}
