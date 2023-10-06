<?php

namespace App\Http\Requests\Api;

use App\Models\Fabric\InboundEndpoint;
use App\Models\Tapestry\Service;
use App\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreInboundEndpointRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', InboundEndpoint::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'integration_id' => ['required', 'integer', 'exists:integrations,id'],
            'service_id' => ['required', 'integer', 'unique:inbound_endpoints,service_id', function ($attribute, $value, $fail) {
                if (!Service::find($value)) {
                    $fail('The service does not exist.');
                }
            }],
            'slug' => ['required', 'string', 'alpha_dash', 'min:3', 'max:36', Rule::unique('inbound_endpoints')->where('integration_id', $this->integration_id)->where('slug', $this->slug), new Slug],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'slug' => 'vanity url',
        ];
    }

    /**
     * Prepare incoming data for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::lower($this->slug),
        ]);
    }
}
