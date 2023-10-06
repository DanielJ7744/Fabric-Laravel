<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Queries\ServiceLogQuery;
use App\Models\Tapestry\ServiceLog;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Resources\Tapestry\ServiceLogResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ServiceLogQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, ServiceLogQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', ServiceLog::class);

        $serviceLogs = $query
            ->paginate();

        return ServiceLogResource::collection($serviceLogs);
    }

    /**
     * Display a Service Log
     *
     * @param ServiceLog $serviceLog
     * @param ServiceLogQuery $query
     *
     * @return ServiceLogResource
     *
     * @throws AuthorizationException
     */
    public function show(ServiceLog $serviceLog, ServiceLogQuery $query): ServiceLogResource
    {
        $this->authorize('view', ServiceLog::class);

        $serviceLog = $query
            ->whereKey($serviceLog)
            ->firstOrFail();

        return new ServiceLogResource($serviceLog);
    }
}
