<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Support\Facades\Log;

class S3Service extends SystemAuthAbstract
{
    protected S3Client $s3Client;

    public function __construct(array $attributes, S3Client $s3Client)
    {
        parent::__construct($attributes);
        $this->s3Client = $s3Client;
    }

    public static function getRules(): array
    {
        return [
            'key' => [
                'required',
                'string',
            ],
            'secret' => [
                'required',
                'string',
            ],
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'key' => [
                'filled',
                'string',
            ],
            'secret' => [
                'filled',
                'string',
            ],
        ];
    }

    public function authenticate(): ?array
    {
        try {
            return $this->s3Client->listBuckets()->get('@metadata');
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }
    }

    public function verify(?array $authResult): bool
    {
        return isset($authResult['statusCode']) && $authResult['statusCode'] === 200;
    }

    public static function getObfuscatedFields(): array
    {
        return ['secret'];
    }
}
