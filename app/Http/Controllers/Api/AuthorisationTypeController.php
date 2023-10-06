<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Queries\AuthorisationTypeQuery;
use App\Models\Fabric\AuthorisationType;
use App\Http\Resources\AuthorisationTypeResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorisationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param AuthorisationTypeQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, AuthorisationTypeQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', AuthorisationType::class);

        $authorisationTypes = $query->paginate();

        return AuthorisationTypeResource::collection($authorisationTypes);
    }

    /**
     * Display the specified resource.
     *
     * @param AuthorisationType $authorisationType
     * @param AuthorisationTypeQuery $query
     *
     * @return AuthorisationTypeResource
     *
     * @throws AuthorizationException
     */
    public function show(AuthorisationType $authorisationType, AuthorisationTypeQuery $query): AuthorisationTypeResource
    {
        $this->authorize('view', $authorisationType);

        $authorisationType = $query
            ->whereKey($authorisationType)
            ->firstOrFail();

        return new AuthorisationTypeResource($authorisationType);
    }
}
