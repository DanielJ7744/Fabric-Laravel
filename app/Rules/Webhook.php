<?php

namespace App\Rules;

use App\Facades\SystemWebhook;
use App\Models\Tapestry\Service;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Webhook implements Rule
{
    protected array $rules;

    protected string $messages;

    public function __construct(int $serviceId)
    {
        try {
            $this->rules = SystemWebhook::getRules(Service::find($serviceId)->getSourceSystem()->factory_name);
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
        $validator = Validator::make($value, $this->rules);
        $passes = $validator->passes();
        $this->messages = implode(' ', $validator->getMessageBag()->all());

        return $passes;
    }

    public function message(): string
    {
        return $this->messages;
    }
}
