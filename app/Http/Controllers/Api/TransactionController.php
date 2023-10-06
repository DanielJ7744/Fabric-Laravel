<?php

namespace App\Http\Controllers\Api;

use App\Facades\Hasura;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexTransactionsRequest;
use App\Models\Fabric\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexTransactionsRequest $request
     *
     * @return Response
     */
    public function index(IndexTransactionsRequest $request)
    {
        $format = $request->format ?: 'daily';
        $end = $request->end ? Carbon::parse($request->end) : now();
        $start = $request->start ? Carbon::parse($request->start) : now()->subDays($format === 'hourly' ? 0 : 60);
        $usernames = Company::current()->getIntegrationUsernames();

        $transactionCounts = Cache::remember(
            "transactions.$format.$start.$end",
            now()->addMinutes(30),
            fn () => Hasura::transactions($usernames, $start, $end, $format)
        );

        return response()->json([
            'data' => $transactionCounts
        ]);
    }
}
