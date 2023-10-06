<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Queries\SystemAuthorisationTypeQuery;
use App\Models\Fabric\SystemAuthorisationType;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Resources\SystemAuthorisationTypeResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SystemAuthorisationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param SystemAuthorisationTypeQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, SystemAuthorisationTypeQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', SystemAuthorisationType::class);

        $systemAuthorisationTypes = $query->paginate();

        return SystemAuthorisationTypeResource::collection($systemAuthorisationTypes);
    }

    /**
     * Display the specified resource.
     *
     * @param SystemAuthorisationType $systemAuthorisationType
     * @param SystemAuthorisationTypeQuery $query
     *
     * @return SystemAuthorisationTypeResource
     *
     * @throws AuthorizationException
     */
    public function show(SystemAuthorisationType $systemAuthorisationType, SystemAuthorisationTypeQuery $query): SystemAuthorisationTypeResource
    {
        $this->authorize('view', $systemAuthorisationType);

        $systemAuthorisationType = $query
            ->whereKey($systemAuthorisationType)
            ->firstOrFail();

        return new SystemAuthorisationTypeResource($systemAuthorisationType);
    }
}
