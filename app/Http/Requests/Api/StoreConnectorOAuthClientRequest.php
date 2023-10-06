<?php

namespace App\Http\Requests\Api;

use App\Models\Fabric\OauthClient;
use Illuminate\Foundation\Http\FormRequest;

class StoreConnectorOAuthClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', [OauthClient::class, $this->connector]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }
}
