<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use Illuminate\Support\Facades\Log;
use phpseclib\Net\SFTP;

class SekoService extends SystemAuthAbstract
{
	protected SFTP $sftpClient;

	public function __construct(array $attributes, SFTP $sftpClient)
	{
		parent::__construct($attributes);
		$this->sftpClient = $sftpClient;
	}
	public static function getRules(): array
	{
		return [
			'ftp_username' => [
				'required',
				'string',
			],
			'ftp_password' => [
				'required',
				'string',
			],
			'ftp_port' => [
				'required',
				'integer',
			],
			'ftp_passive' => [
				'required',
				'string'
			],
			'ftp_host' => [
				'required',
				'string',
			]
		];
	}

	public static function getUpdateRules(): array
	{
		return [
			'ftp_username' => [
				'filled',
				'string',
			],
			'ftp_password' => [
				'filled',
				'string',
			],
			'ftp_port' => [
				'filled',
				'integer',
			],
			'ftp_passive' => [
				'filled',
				'boolean'
			],
			'ftp_host' => [
				'filled',
				'string',
			]
		];
	}

	public function authenticate(): ?array
	{
		try {
			$authResult = $this->sftpClient->login($this->attributes['ftp_username'], $this->attributes['ftp_password']);
			$this->sftpClient->disconnect();
		} catch (Exception $exception) {
			Log::warning($exception->getMessage());

			return null;
		}

		return ['valid' => $authResult];
	}

	public function verify(?array $authResult): bool
	{
		return isset($authResult['valid']) &&  $authResult['valid'] === true;
	}

	public static function getTapestryFormat(array $credentials): array
	{
		//Convert flat structure to array
		$ftpFields = [
			'host',
			'port',
			'username',
			'password',
			'passive'
		];
		$credentials['ftp_connection'] = [];
		foreach ($ftpFields as $field) {
			$credentials['ftp_connection'][$field] = $credentials[sprintf('ftp_%s', $field)];
			unset($credentials['ftp_' . $field]);
		}

        return $credentials;
    }

	public static function getFabricFormat(array $credentials): array
	{
        //Convert array to flat structure
		foreach ($credentials['ftp_connection'] as $key => $value) {
			$credentials[sprintf('ftp_%s', $key)] = $value;
		}
		unset($credentials['ftp_connection']);

        return $credentials;
    }

	public static function getObfuscatedFields(): array
	{
		return ['ftp_password'];
	}
}
