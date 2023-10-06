<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckForAdminRole
{
    /**
     * Set the authenticated user's company as the tenant.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->hasRole('patchworks admin')) {
            abort(Response::HTTP_FORBIDDEN, 'User does not have the correct role');
        }

        return $next($request);
    }
}
