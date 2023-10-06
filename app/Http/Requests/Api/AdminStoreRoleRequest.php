<?php

namespace App\Http\Requests\Api;

use App\Rules\CompanyName;
use App\Rules\RoleName;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreRoleRequest extends FormRequest
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
            'name'              => ['required', 'string', 'max:125', 'unique:roles,name', new RoleName()],
            'guard_name'        => ['required', 'string', 'max:125'],
            'patchworks_role'   => ['required', 'boolean'],
        ];
    }
}
