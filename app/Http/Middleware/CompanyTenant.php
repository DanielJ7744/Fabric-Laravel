<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CompanyTenant
{
    /**
     * Set the authenticated user's company as the tenant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (is_null(auth()->user()->company_id)) {
            abort(Response::HTTP_FORBIDDEN, 'Your account is not associated with a company.');
        }

        auth()->user()->company->makeCurrent();

        return $next($request);
    }
}
