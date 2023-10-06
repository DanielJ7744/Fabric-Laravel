<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Queries\CompanyQuery;
use App\Models\Fabric\Company;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreCompanyRequest;
use App\Http\Requests\Api\AdminUpdateCompanyRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminCompanyController extends Controller
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

        $companies = $query->paginate();

        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreCompanyRequest $request
     *
     * @return CompanyResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreCompanyRequest $request): CompanyResource
    {
        $this->authorize('create', Company::class);

        $company = Company::create($request->validated());

        return new CompanyResource($company);
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

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateCompanyRequest $request
     * @param Company $company
     *
     * @return CompanyResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateCompanyRequest $request, Company $company): CompanyResource
    {
        $this->authorize('update', $company);

        $company->update($request->validated());

        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Company $company): JsonResponse
    {
        $this->authorize('delete', $company);

        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully.'
        ]);
    }
}
