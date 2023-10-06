<?php

namespace App\Http\Controllers\Api;

use App\Http\Helpers\PaymentMapHelper;
use App\Http\Helpers\ShipmentMapHelper;
use App\Http\Requests\Api\UpdateShipmentMapRequest;
use App\Http\Resources\Tapestry\ShipmentMapResource;
use App\Models\Fabric\Integration;
use App\Models\Tapestry\PaymentMap;
use App\Models\Tapestry\ShipmentMap;
use App\Queries\ServiceTemplateQuery;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Fabric\ServiceTemplate;
use App\Http\Resources\ServiceTemplateResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreServiceTemplateRequest;
use App\Http\Requests\Api\AdminUpdateServiceTemplateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class IntegrationShipmentMapController extends Controller
{
    private ShipmentMapHelper $shipmentMapHelper;

    public function __construct(ShipmentMapHelper $shipmentMapHelper)
    {
        $this->shipmentMapHelper = $shipmentMapHelper;
    }

    /**
     * Get payment map
     *
     * @param Integration $integration
     *
     * @return ShipmentMapResource
     *
     * @throws AuthorizationException
     * @throws GuzzleException
     */
    public function index(Integration $integration): ShipmentMapResource
    {
        $this->authorize('viewAny', ShipmentMap::class);

        $result = $this->shipmentMapHelper->get($integration->server, $integration->username);

        if (!isset($result['data']) || $result['data'] === []) {
            abort(404, 'No shipment map was found.');
        }

        return new ShipmentMapResource((object) $result['data']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateShipmentMapRequest $request
     * @param Integration $integration
     *
     * @return ShipmentMapResource
     *
     * @throws AuthorizationException
     * @throws GuzzleException
     */
    public function store(UpdateShipmentMapRequest $request, Integration $integration): ShipmentMapResource
    {
        $this->authorize('create', ShipmentMap::class);

        $result = $this->shipmentMapHelper->post($integration->server, $integration->username, $request->validated());

        if (!isset($result['data']) || $result['data'] === []) {
            abort(500, 'Failed to update the shipment map.');
        }

        return new ShipmentMapResource((object)$result['data']);
    }
}
