<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class IndexReportSyncsRequest extends FormRequest
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
            'start'         => ['filled', 'date', 'after:-6 month', 'max:30'],
            'end'           => ['filled', 'date', 'after:start', 'max:30', 'required_with:start'],
            'days'          => ['filled', 'integer'],
            'status'        => ['filled', 'string'],
            'type'          => ['filled', 'string'],
            'system_chain'  => ['filled', 'string'],
            'search_term'   => ['filled', 'string'],
        ];
    }
}
