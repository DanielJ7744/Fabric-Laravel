<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Queries\SubscriptionQuery;
use App\Models\Fabric\Subscription;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param SubscriptionQuery $query
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, SubscriptionQuery $query): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Subscription::class);

        $subscriptions = $query->get();

        return SubscriptionResource::collection($subscriptions);
    }

    /**
     * Display the specified resource.
     *
     * @param Subscription $subscription
     * @param SubscriptionQuery $query
     *
     * @return SubscriptionResource
     *
     * @throws AuthorizationException
     */
    public function show(Subscription $subscription, SubscriptionQuery $query): SubscriptionResource
    {
        $this->authorize('view', $subscription);

        $subscription = $query
            ->whereKey($subscription)
            ->firstOrFail();

        return new SubscriptionResource($subscription);
    }
}
