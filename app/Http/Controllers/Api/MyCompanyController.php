<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Requests\Api\UpdateMyCompanyRequest;
use App\Queries\CompanyQuery;
use Illuminate\Auth\Access\AuthorizationException;

class MyCompanyController extends Controller
{
    /**
     * Update the authenticated user's password.
     *
     * @param UpdateMyCompanyRequest $request
     *
     * @return CompanyResource
     *
     * @throws AuthorizationException
     */
    public function update(UpdateMyCompanyRequest $request): CompanyResource
    {
        $company = auth()->user()->company;

        $this->authorize('update', $company);

        $company->update($request->validated());

        return new CompanyResource($company);
    }

    /**
     * Show the user's company
     *
     * @param CompanyQuery $query
     * @return CompanyResource
     *
     * @throws AuthorizationException
     */
    public function show(CompanyQuery $query): CompanyResource
    {
        $company = $query
            ->whereKey(auth()->user()->company)
            ->firstOrFail();

        $this->authorize('view', $company);

        return new CompanyResource($company);
    }
}
