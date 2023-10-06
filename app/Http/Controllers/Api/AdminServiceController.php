<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AdminUpdateServiceRequest;
use App\Http\Resources\Tapestry\ServiceResource;
use App\Models\Tapestry\Service;

class AdminServiceController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateServiceRequest $request
     * @param Service $service
     *
     * @return ServiceResource
     */
    public function update(AdminUpdateServiceRequest $request, Service $service): ServiceResource
    {
        $service->update($request->validated());

        return new ServiceResource($service);
    }
}
