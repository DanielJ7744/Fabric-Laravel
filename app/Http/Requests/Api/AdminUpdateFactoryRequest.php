<?php

namespace App\Http\Requests\Api;

use App\Rules\FilterTemplates\Name;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateFactoryRequest extends FormRequest
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
            'name' => ['filled', 'string', Rule::unique('factories')->ignore($this->factory->id), new Name()],
        ];
    }
}
