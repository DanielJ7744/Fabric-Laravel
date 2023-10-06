<?php

namespace App\Http\Controllers\Api;

use App\Events\CompanySubscriptionUpdated;
use App\Models\Fabric\Company;
use Illuminate\Http\JsonResponse;
use App\Models\Fabric\Subscription;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class AdminCompanySubscriptionController extends Controller
{
    /**
     * Add a subscription to a company.
     *
     * @param Company $company
     * @param Subscription $subscription
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function update(Company $company, Subscription $subscription): JsonResponse
    {
        $this->authorize('add', $subscription);

        $company->subscriptions()->syncWithoutDetaching($subscription);
        event(new CompanySubscriptionUpdated($company, $subscription));

        return response()->json([
            'message' => 'Subscription assigned to company successfully.'
        ]);
    }

    /**
     * Remove a subscription from a company
     *
     * @param Company $company
     * @param Subscription $subscription
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Company $company, Subscription $subscription): JsonResponse
    {
        $this->authorize('remove', $subscription);

        $company->subscriptions()->detach($subscription);

        return response()->json([
            'message' => 'Subscription removed from company successfully.'
        ]);
    }
}
