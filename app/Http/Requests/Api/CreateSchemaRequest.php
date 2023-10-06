<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateSchemaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'entity_id' => ['required', 'exists:entities,id'],
            'company_id' => ['sometimes', 'exists:companies,id'],
            'source_api_version' => ['required', 'numeric'],
            'type' => ['nullable', 'string', 'in:example,json_schema'],
            'data' => ['required', 'string']
        ];
    }
}
