<?php

namespace App\Http\Controllers\ScriptLibrary;

use App\Http\Controllers\GatewayController;
use App\Http\Requests\ScriptLibrary\BatchRequest;
use GuzzleHttp\Psr7\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Support\Reflector;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BatchController extends GatewayController
{
    private array $errors = [];

    private const SCRIPTS_API_PREFIX = 'api/v1/transform-scripts/';

    /**
     * @throws ReflectionException
     */
    public function handle(BatchRequest $batchRequest): ResponseInterface
    {
        $requests = collect($batchRequest->validated()['batch']);
        $requests = $requests->map(function ($request) {
            if (!isset($request['relative_uri'], $request['method'])) {
                return false;
            }

            $route = $this->getRoute($request);
            if (!$route) {
                return false;
            }

            $reflectionMethod = new ReflectionMethod($route->getController(), $route->getActionMethod());
            $formRequest = $this->getFormRequest($reflectionMethod->getParameters());
            if (!$this->authorise($formRequest)) {
                $this->setError($request, 403, 'Forbidden.');

                return false;
            }

            if (!$this->controllerSupportsBatching($reflectionMethod->class)) {
                $this->setError($request, 405, 'This endpoint does not support request batching.');

                return false;
            }

            $request['relative_uri'] = $this->getScriptLibraryUri($request['relative_uri'], $reflectionMethod->class);

            return $request;
        })->filter();

        if (!empty($this->errors)) {
            return new Response(207, [], json_encode($this->errors, true));
        }

        $batchRequest->replace(['batch' => $requests->toArray()]);

        return $this->forward($batchRequest, 'POST', '/batch', [], $batchRequest->input());
    }

    protected function getRoute(array $request): ?Route
    {
        try {
            return app('router')
                ->getRoutes()
                ->match(app('request')
                ->create($request['relative_uri'], $request['method']));
        } catch (NotFoundHttpException|MethodNotAllowedHttpException $exception) {
            $this->setError($request, $exception->getStatusCode(), $exception->getMessage());
            return null;
        }
    }

    protected function getFormRequest(array $reflectionParameters): ?FormRequest
    {
        $formRequestClass = collect($reflectionParameters)->first(function ($parameter) {
            return Reflector::isParameterSubclassOf($parameter, FormRequest::class);
        });
        $controller = !is_null($formRequestClass) ? Reflector::getParameterClassName($formRequestClass) : null;

        return !is_null($controller) ? new $controller : null;
    }

    protected function authorise(?FormRequest $formRequest): bool
    {
        if (is_null($formRequest) || !method_exists($formRequest, 'authorize')) {
            return false;
        }

        try {
            return $formRequest->authorize()->allowed();
        } catch (AuthorizationException $exception) {
            return false;
        }
    }

    protected function setError(array $request, int $code, string $message): void
    {
        $this->errors[] = [
            'code' => $code,
            'message' => $message,
            'original' => [
                'method' => $request['method'],
                'relative_uri' => $request['relative_uri'],
            ],
        ];
    }

    /**
     * Api controller must implement BatchRedirectInterface
     * This is required to support translating the URI from the fabric format to the script library format.
     *
     * @param string $controller
     *
     * @return bool
     */
    protected function controllerSupportsBatching(string $controller): bool
    {
        return method_exists($controller, 'getScriptLibraryUri');
    }

    protected function getScriptLibraryUri(string $uri, string $controller): string
    {
        return $controller::getScriptLibraryUri(str_replace(self::SCRIPTS_API_PREFIX, '', $uri));
    }
}
