<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckForPatchworksRole
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
        if (!auth()->user()->hasRole('patchworks admin') && !auth()->user()->hasRole('patchworks user')) {
            abort(Response::HTTP_FORBIDDEN, 'User does not have the correct role');
        }

        return $next($request);
    }
}
