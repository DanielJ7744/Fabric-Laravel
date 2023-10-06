<?php

namespace App\Rules;

use Exception;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Tapestry\Connector;
use Illuminate\Contracts\Validation\Rule;

class CommonRefUnique implements Rule
{
    protected Integration $integration;

    protected System $system;

    public function __construct(int $integrationId, int $systemId)
    {
        try {
            $this->integration = Integration::find($integrationId);
            $this->system = System::find($systemId);
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
        $connector = (new Connector())->setIdxTable($this->integration->username)
            ->where('common_ref', '=', $value)
            ->where('system_chain', '=', $this->system->name)
            ->first();
        if ($connector === null) {
            return true;
        }

        return false;
    }

    public function message(): string
    {
        return 'Please enter a unique value';
    }
}
