<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Queries\ServiceOptionQuery;
use App\Models\Fabric\ServiceOption;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceOptionResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ServiceOptionQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, ServiceOptionQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', ServiceOption::class);

        $serviceOptions = $query->paginate($request->perPage);

        return ServiceOptionResource::collection($serviceOptions);
    }

    /**
     * Display the specified resource.
     *
     * @param ServiceOption $serviceOption
     * @param ServiceOptionQuery $query
     *
     * @return ServiceOptionResource
     *
     * @throws AuthorizationException
     */
    public function show(ServiceOption $serviceOption, ServiceOptionQuery $query): ServiceOptionResource
    {
        $this->authorize('view', $serviceOption);

        $serviceOption = $query->whereKey($serviceOption)->firstOrFail();

        return new ServiceOptionResource($serviceOption);
    }
}
