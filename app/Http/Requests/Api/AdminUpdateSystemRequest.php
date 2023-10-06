<?php

namespace App\Http\Requests\Api;

use App\Rules\Systems\Name;
use App\Rules\SystemWebsite;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateSystemRequest extends FormRequest
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
            'system_type_id'                    => ['filled', 'integer', 'exists:system_types,id'],
            'name'                              => ['filled', 'string', 'min:3', 'max:128', new Name(), Rule::unique('systems')->ignore($this->system->id)],
            'factory_name'                      => ['filled', 'string', 'min:3', 'max:255', new Name()],
            'slug'                              => ['nullable', 'string', 'min:3', 'max:255'],
            'website'                           => ['filled', 'string', 'min:3', 'max:255', new SystemWebsite()],
            'oauth'                             => ['filled', 'boolean'],
            'popular'                           => ['filled', 'boolean'],
            'description'                       => ['filled', 'string', 'min:3', 'max:255'],
            'status'                            => ['filled', 'in:active,inactive,development,hidden'],
            'documentation_link'                => ['nullable', 'string', 'min:3', 'max:255'],
            'documentation_link_description'    => ['nullable', 'string', 'min:3', 'max:255'],
            'environment_suffix_title'          => ['nullable', 'string', 'min:3', 'max:255'],
            'image'                             => ['nullable', 'image', 'mimes:svg']
        ];
    }
}
