<?php

namespace App\Exceptions;

use CloudCreativity\LaravelJsonApi\Exceptions\HandlesErrors;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class Handler extends ExceptionHandler
{
    use HandlesErrors;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        JsonApiException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $e)
    {
        if ($this->isJsonApi($request, $e)) {
            return $this->renderJsonApi($request, $e);
        }

        return parent::render($request, $e);
    }

    protected function prepareException(Exception $e)
    { 
        if ($e instanceof JsonApiException) {
            return $this->prepareJsonApiException($e);
        }

        return parent::prepareException($e);
    }
}
