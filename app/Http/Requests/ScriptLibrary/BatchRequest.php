<?php

namespace App\Http\Requests\ScriptLibrary;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
{
    /**
     * Called from ValidatesWhenResolvedTrait by FormRequestServiceProvider
     *
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Called from ValidatesWhenResolvedTrait by FormRequestServiceProvider
     *
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'batch' => 'required|array|min:1',
            'batch.*.method' => 'required_with:batch|string',
            'batch.*.relative_uri' => 'required_with:batch|string',
            'batch.*.body' => 'filled|array'
        ];
    }
}
