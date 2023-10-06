<?php

namespace App\Http\Controllers\Api;

use App\Http\Helpers\PaymentMapHelper;
use App\Http\Requests\Api\UpdatePaymentMapRequest;
use App\Http\Resources\Tapestry\PaymentMapResource;
use App\Models\Fabric\Integration;
use App\Models\Tapestry\PaymentMap;
use GuzzleHttp\Exception\GuzzleException;
use App\Http\Controllers\Controller;
use App\Models\Fabric\ServiceTemplate;
use App\Http\Resources\ServiceTemplateResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminUpdateServiceTemplateRequest;

class IntegrationPaymentMapController extends Controller
{
    private PaymentMapHelper $paymentMapHelper;

    public function __construct(PaymentMapHelper $paymentMapHelper)
    {
        $this->paymentMapHelper = $paymentMapHelper;
    }

    /**
     * Get payment map
     *
     * @param Integration $integration
     *
     * @return PaymentMapResource
     *
     * @throws AuthorizationException
     * @throws GuzzleException
     */
    public function index(Integration $integration): PaymentMapResource
    {
        $this->authorize('viewAny', PaymentMap::class);

        $result = $this->paymentMapHelper->get($integration->server, $integration->username);

        if (!isset($result['data']) || $result['data'] === []) {
            abort(404, 'No payment map was found.');
        }

        return new PaymentMapResource((object)$result['data']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePaymentMapRequest $request
     * @param Integration $integration
     *
     * @return PaymentMapResource
     *
     * @throws AuthorizationException
     * @throws GuzzleException
     */
    public function store(UpdatePaymentMapRequest $request, Integration $integration): PaymentMapResource
    {
        $this->authorize('create', PaymentMap::class);

        $result = $this->paymentMapHelper->post($integration->server, $integration->username, $request->validated());

        if (!isset($result['data']) || $result['data'] === []) {
            abort(500, 'Failed to update the payment map.');
        }

        return new PaymentMapResource((object)$result['data']);
    }
}
