<?php

namespace App\Http\Requests\Api;

use App\Rules\FilterTemplates\FilterKey;
use App\Rules\FilterTemplates\Name;
use App\Rules\FilterTemplates\PWValueField;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreFilterTemplateRequest extends FormRequest
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
            'name'              => ['required', 'string', new Name()],
            'filter_key'        => ['required', 'string', new FilterKey()],
            'factory_system_id' => ['required', 'exists:factory_systems,id'],
            'template'          => ['required', 'json'],
            'note'              => ['string', 'nullable'],
            'pw_value_field'    => ['required', 'string', new PWValueField()],
        ];
    }
}
