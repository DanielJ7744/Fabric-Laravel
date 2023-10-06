<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventLogResource;
use App\Models\Fabric\EventLog;
use App\Queries\EventLogQuery;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Queries\EventLogQuery  $query
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(EventLogQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', EventLog::class);

        $logs = $query->latest()->paginate(request()->perPage ?? 500);

        return EventLogResource::collection($logs);
    }
}
