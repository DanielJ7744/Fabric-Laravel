<?php

namespace App\Http\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ElasticsearchHelper
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $path
     * @param array $data
     *
     * @throws GuzzleException
     */
    public function post(string $path, array $data): void
    {
        $this->execute($path, 'POST', $data);
    }

    /**
     * @param string $path
     * @param array $data
     *
     * @throws GuzzleException
     */
    public function put(string $path, array $data): void
    {
        $this->execute($path, 'PUT', $data);
    }

    /**
     * @param string $path
     *
     * @return array
     *
     * @throws GuzzleException
     */
    public function get(string $path): array
    {
        return $this->execute($path, 'GET');
    }

    /**
     * @param string $path
     *
     * @throws GuzzleException
     */
    public function delete(string $path): void
    {
        $this->execute($path, 'DELETE');
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $data
     *
     * @return array
     *
     * @throws GuzzleException
     */
    private function execute(string $path, string $method, array $data = []): array
    {
        $url = sprintf('%s/%s', config('elasticsearch.url'), $path);
        $username = config('elasticsearch.username');
        $password = config('elasticsearch.password');
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => sprintf('Basic %s', base64_encode(sprintf('%s:%s', $username, $password))),
            ],
        ];

        if ($method === 'POST' || $method === 'PUT') {
            $options['json'] = $data;
        }

        return json_decode((string) $this->client->request($method, $url, $options)->getBody(), true);
    }
}
