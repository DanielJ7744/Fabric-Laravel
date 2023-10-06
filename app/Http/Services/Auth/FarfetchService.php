<?php

namespace App\Http\Services\Auth;

use Exception;
use SoapClient;
use Carbon\Carbon;
use App\Rules\Https;
use Illuminate\Support\Facades\Log;
use App\Http\Abstracts\SystemAuthAbstract;

class FarfetchService extends SystemAuthAbstract
{
    protected SoapClient $soapClient;

    public function __construct(array $attributes, SoapClient $soapClient)
    {
        parent::__construct($attributes);
        $this->soapClient = $soapClient;
    }

    public static function getRules(): array
    {
        return [
            'retail_url' => [
                'required',
                'string',
                new Https(),
            ],
            'sales_url' => [
                'required',
                'string',
                new Https(),
            ],
            'key' => [
                'required',
                'string'
            ],
            'store_id' => [
                'required',
                'string'
            ],
            'large_box_id' => [
                'required',
                'string'
            ],
            'null_box_id' => [
                'required',
                'string'
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'retail_url' => [
                'filled',
                'string',
                new Https(),
            ],
            'sales_url' => [
                'filled',
                'string',
                new Https(),
            ],
            'key' => [
                'filled',
                'string'
            ],
            'store_id' => [
                'filled',
                'string'
            ],
            'large_box_id' => [
                'filled',
                'string'
            ],
            'null_box_id' => [
                'filled',
                'string'
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            $this->soapClient->GetOrdersByDate([
                'Key' => $this->attributes['key'],
                'StartDate' => Carbon::now()->subDay()->format('Y-m-d'),
                'EndDate' => Carbon::now()->addDay()->format('Y-m-d')
            ]);
            $authResult = ['success' => true];
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return $authResult;
    }

    public function verify(?array $authResult): bool
    {
        return !is_null($authResult) && isset($authResult['success']) && $authResult['success'] === true;
    }

    public static function getObfuscatedFields(): array
    {
        return ['key'];
    }
}
