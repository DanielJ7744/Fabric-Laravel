<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Queries\CompanyQuery;
use App\Models\Fabric\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PatchworksCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param CompanyQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, CompanyQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Company::class);

        $companies = $query->paginate($request->perPage);

        return CompanyResource::collection($companies);
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @param CompanyQuery $query
     *
     * @return CompanyResource
     *
     * @throws AuthorizationException
     */
    public function show(Company $company, CompanyQuery $query): CompanyResource
    {
        $this->authorize('view', $company);

        $company = $query
            ->whereKey($company)
            ->firstOrFail();

        return new CompanyResource($company);
    }
}
