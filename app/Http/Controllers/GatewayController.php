<?php

namespace App\Http\Controllers;

use App\Exceptions\GatewayConfigException;
use App\Exceptions\GatewayNameException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

class GatewayController extends Controller
{
    private Client $httpClient;

    protected array $jsonHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ];

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Forwards any call to the API url with $path appended
     */
    public function forward(
        Request $request,
        string $method,
        string $path,
        array $headers = [],
        array $body = []
    ): ResponseInterface {
        $url = $this->generateFullUrl($request, $path);
        return $this->makeCall($method, $url, $headers, $body);
    }

    /**
     * Forwards any GET call
     * To the API url with $path appended
     * @param string $path
     */
    public function forwardGet(Request $request, string $path): ResponseInterface
    {
        $url = $this->generateFullUrl($request, $path);
        return $this->makeCall('GET', $url, $this->jsonHeaders);
    }

    /**
     * Forwards any POST call
     * To the API url with $path appended
     * And passes the contents of $request as the body
     *
     * @param string $path
     */
    public function forwardPost(Request $request, string $path): ResponseInterface
    {
        $url = $this->generateFullUrl($request, $path);
        return $this->makeCall('POST', $url, [], $request->input());
    }

    /**
     * Gets the config relevant for the current Gateway
     * @return array
     */
    protected function getGatewayConfig(): array
    {
        $configName = $this->getGatewayName();
        $config = Config::get(sprintf(
            'gateway.%s',
            $configName
        ), []);

        if (empty($config)) {
            throw new GatewayConfigException(sprintf('No config found for gateway with name "%s"', $configName));
        }

        return $config;
    }

    /**
     * Gets the name of the gateway
     * Based on group/route name
     * @return string
     * @throws \Exception
     */
    protected function getGatewayName(): string
    {
        // use the name of the route group to determine the relevant config name
        $routeName = Route::current()->getName();
        $firstRouteNameSegment = Arr::first(explode('.', $routeName));
        $gatewayName = Str::replaceFirst('-', '_', $firstRouteNameSegment);

        if (!$gatewayName) {
            throw new GatewayNameException("Failed to get Gateway name: Route name missing - ensure the route is within a group with the relevant name applied, or has the gateway name set as the name property for the route");
        }

        return Str::snake($gatewayName);
    }

    /**
     * Gets the API url from the config
     * @return string
     */
    protected function getApiUrl(): string
    {
        $config = $this->getGatewayConfig();
        return $config['api_url'];
    }

    protected function makeCall(string $method, string $url, array $headers = [], array $body = []): ResponseInterface
    {
        if ($this->shouldCreateLogs()) {
            Log::info(sprintf(
                'Gateway: proxying %s request to %s',
                $method,
                $url
            ), $this->contextForLogging());
        }

        try {
            return $this->httpClient->request($method, $url, [
                'headers' => $headers,
                'json' => $body
            ]);
        } catch (ClientException $e) {
            if ($this->shouldCreateLogs()) {
                Log::warning(sprintf(
                    'Gateway: [status: %d] on proxying %s request to %s',
                    $e->getCode(),
                    $method,
                    $url
                ), $this->contextForLogging());
            }
            return $e->getResponse();
        }
    }

    private function generateFullUrl(Request $request, string $path): string
    {
        $queryString = $request->getQueryString();

        // sanitise the query string
        $queryStringEntities = htmlentities($queryString);
        parse_str(html_entity_decode($queryStringEntities), $queryStringArray);
        $queryStringArray = filter_var_array($queryStringArray, FILTER_SANITIZE_ENCODED);
        $queryStringClean = Arr::query($queryStringArray);

        return sprintf(
            '%s/%s%s',
            $this->getApiUrl(),
            $path,
            $queryStringClean ? sprintf('?%s', $queryStringClean) : ''
        );
    }

    private function contextForLogging(): array
    {
        return [
            'type' => 'api_gateway',
            'gateway_name' => $this->getGatewayName(),
            'gateway_url' => $this->getApiUrl()
        ];
    }

    /**
     * Whether logs should be created in normal operation
     * @return bool
     */
    private function shouldCreateLogs(): bool
    {
        return Arr::get($this->getGatewayConfig(), 'create_logs', false);
    }
}
