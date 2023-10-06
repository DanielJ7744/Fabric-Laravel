<?php

namespace App\Rules;

use App\Facades\SystemAuth;
use App\Models\Fabric\System;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Credentials implements Rule
{
    protected System $system;

    protected bool $update;

    protected string $messages;

    public function __construct(int $systemId, bool $update = false)
    {
        try {
            $this->system = System::find($systemId);
            $this->update = $update;
        } catch (Exception $exception) {
            abort($exception->getCode(), $exception->getMessage());
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $rules = $this->update
            ? SystemAuth::getUpdateRules($this->system->factory_name, $value)
            : SystemAuth::getRules($this->system->factory_name, $value);
        $validator = Validator::make($value, $rules);
        $passes = $validator->passes();
        $this->messages = implode(' ', $validator->getMessageBag()->all());

        return $passes;
    }

    public function message(): string
    {
        return $this->messages;
    }
}
