<?php

namespace App\Rules;

use App\Models\Fabric\AuthorisationType;
use App\Models\Fabric\System;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class SystemHasAuthType implements Rule
{
    private string $authType;

    public function __construct(string $authType)
    {
        $this->authType = $authType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return System::whereHas('systemAuthorisationTypes', fn (Builder $query) => $query
            ->where('authorisation_type_id', AuthorisationType::firstWhere('name', $this->authType)->id)
        )->find($value)->exists ?? false;
    }

    public function message(): string
    {
        return sprintf('The :attribute must support %s authorisation.', $this->authType);
    }
}
