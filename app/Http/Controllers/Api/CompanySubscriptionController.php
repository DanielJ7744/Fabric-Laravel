<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Fabric\Company;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanySubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $subscriptions = Company::current()->subscriptions;

        return SubscriptionResource::collection($subscriptions)
            ->additional(['summary' => [
                'usage' => Company::current()->subscriptionUsage(),
                'allowance' => Company::current()->subscriptionAllowance(),
            ]]);
    }
}
