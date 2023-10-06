<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class IndexTransactionsRequest extends FormRequest
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
            'format' => ['nullable', 'string', 'in:daily,hourly'],
            'start' => ['nullable', 'date', 'after:-6 month', 'max:30'],
            'end' => ['nullable', 'date', 'after:start', 'max:30', 'required_with:start'],
        ];
    }
}
