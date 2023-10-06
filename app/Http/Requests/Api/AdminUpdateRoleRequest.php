<?php

namespace App\Http\Requests\Api;

use App\Rules\RoleName;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateRoleRequest extends FormRequest
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
            'name'              => [
                'filled',
                'string',
                'max:125',
                Rule::unique('roles')->ignore($this->role->id),
                new RoleName()
            ],
            'guard_name'        => ['filled', 'string', 'max:125'],
            'patchworks_role'   => ['filled', 'boolean'],
        ];
    }
}
