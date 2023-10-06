<?php

namespace App\Rules\Systems;

use App\Http\Managers\SystemAuthManager;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class SystemAuthDriver implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return method_exists(SystemAuthManager::class, sprintf('create%sDriver', Str::studly($value)));
    }

    public function message(): string
    {
        return 'The system :attribute does not have a driver within the system auth manager.';
    }
}
