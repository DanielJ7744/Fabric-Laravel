<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use Illuminate\Support\Facades\Log;
use phpseclib\Crypt\PublicKeyLoader;
use phpseclib\Net\SFTP;

class SFTPService extends SystemAuthAbstract
{
    protected SFTP $sftp;

    public function __construct(array $attributes, SFTP $sftp)
    {
        parent::__construct($attributes);
        $this->sftp = $sftp;
    }

    public static function getRules(): array
    {
        return [
            'host' => [
                'required',
                'string',
            ],
            'port' => [
                'required',
                'numeric',
            ],
            'username' => [
                'required',
                'string'
            ],
            'password' => [
                'required_without:public_key',
                'string'
            ],
            'public_key' => [
                'required_without:password',
                'string'
            ]
        ];
    }

    public static function getUpdateRules(): array
    {
        return [
            'host' => [
                'filled',
                'string',
            ],
            'port' => [
                'filled',
                'numeric',
            ],
            'username' => [
                'filled',
                'string'
            ],
            'password' => [
                'filled',
                'string'
            ],
            'public_key' => [
                'filled',
                'string'
            ]
        ];
    }

    public function authenticate(): ?array
    {
        try {
            if (isset($this->attributes['public_key'])) {
                $key = PublicKeyLoader::load($this->attributes['public_key']);
                $authResult = $this->sftp->login($this->attributes['username'], $key);
            } else {
                $authResult = $this->sftp->login($this->attributes['username'], $this->attributes['password']);
            }
        } catch (Exception $exception) {
            Log::warning($exception->getMessage());

            return null;
        }

        return ['status' => $authResult];
    }

    public function verify(?array $authResult): bool
    {
        return !is_null($authResult) && $authResult['status'] === true;
    }

    public static function getObfuscatedFields(): array
    {
        return ['password', 'public_key'];
    }
}
