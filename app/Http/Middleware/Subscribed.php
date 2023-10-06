<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class Subscribed
{
    /**
     * The urls to exclude from the middleware.
     *
     * @var array
     */
    protected $excludedUrls = [
        "api:v1:users.update", // allow updating user's company
        "api:v1:companies.index", // allow loading company list
        "api.v2.subscriptions.index", // allow loading company subscriptions
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array(Route::currentRouteName(), $this->excludedUrls)) {
            return $next($request);
        }

        abort_if(
            optional(auth()->user()->company)->trialExpired(),
            Response::HTTP_UNAUTHORIZED,
            'Trial has expired, please contact support',
            ['trial_status' => 'expired']
        );

        return $next($request);
    }
}
