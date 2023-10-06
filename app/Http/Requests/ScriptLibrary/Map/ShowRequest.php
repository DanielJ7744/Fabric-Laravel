<?php

namespace App\Http\Requests\ScriptLibrary\Map;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;
use function app;

class ShowRequest extends FormRequest
{
    /**
     * Called from ValidatesWhenResolvedTrait by FormRequestServiceProvider
     *
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return app(Gate::class)->authorize('read maps');
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
        return [];
    }
}
