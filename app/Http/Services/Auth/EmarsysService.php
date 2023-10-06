<?php

namespace App\Http\Services\Auth;

use App\Http\Abstracts\SystemAuthAbstract;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use phpseclib\Net\SFTP;

class EmarsysService extends SystemAuthAbstract
{
	protected Client $client;

	protected SFTP $sftpClient;

	public function __construct(array $attributes, Client $client, SFTP $sftpClient)
	{
		parent::__construct($attributes);
		$this->client = $client;
		$this->sftpClient = $sftpClient;
	}

	public static function getRules(): array
	{
		return [
			'username' => [
				'required',
				'string',
			],
			'api_secret' => [
				'required',
				'string',
			],
			'ftp_host' => [
				'required',
				'string',
			],
			'ftp_port' => [
				'required',
				'integer',
				'in:21,22',
			],
			'ftp_username' => [
				'required',
				'string',
			],
			'ftp_password' => [
				'required',
				'string',
			],
			'ftp_passive' => [
				'required',
				'integer',
				'in:0,1',
			],
		];
	}

	public static function getUpdateRules(): array
	{
		return [
			'username' => [
				'filled',
				'string',
			],
			'api_secret' => [
				'filled',
				'string',
			],
			'ftp_host' => [
				'filled',
				'string',
			],
			'ftp_port' => [
				'filled',
				'integer',
			],
			'ftp_username' => [
				'filled',
				'string',
			],
			'ftp_password' => [
				'filled',
				'string',
			],
			'ftp_passive' => [
				'filled',
				'integer'
			],
		];
	}

	public function authenticate(): ?array
	{
		try {
			$apiAuthResult = $this->apiAuth();
			$ftpAuthResult = $this->sftpAuth();
			return ['api' => $apiAuthResult, 'ftp' => $ftpAuthResult];
		} catch (Exception $exception) {
			Log::warning($exception->getMessage());

			return null;
		}
	}

	protected function apiAuth(): ?array
	{
		$nonce = bin2hex(random_bytes(16));
		$timestamp = gmdate("c");
		$passwordDigest = base64_encode(sha1($nonce . $timestamp . $this->attributes['api_secret'], false));

		$xwsseHeader = sprintf(
			'UsernameTokenUsername="%s",PasswordDigest="%s",Nonce="%s",Created="%s"',
			$this->attributes['username'],
			$passwordDigest,
			$nonce,
			$timestamp
		);

		$request = $this->client->get(
			'https://api.emarsys.net/api/v2/settings',
			['headers' => ['X-WSSE' => $xwsseHeader]]
		);

		return json_decode($request->getBody()->getContents(), true);
	}

	protected function sftpAuth(): ?array
	{
		try {
			$authResult = $this->sftpClient->login($this->attributes['ftp_username'], $this->attributes['ftp_password']);
			$this->sftpClient->disconnect();

			return $authResult ? ['ftpConnected' => true] : null;
		} catch (Exception $exception) {
			Log::warning($exception->getMessage());

			return null;
		}
	}

	public function verify(?array $authResult): bool
	{
		return isset($authResult['api']['replyCode'], $authResult['api']['replyText'], $authResult['ftp']['ftpConnected']);
	}

	public static function getObfuscatedFields(): array
	{
		return ['secret', 'ftp_password'];
	}

	public static function getTapestryFormat(array $credentials): array
	{
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
}
