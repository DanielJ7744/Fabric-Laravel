<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Models\Fabric\FilterField;
use App\Http\Controllers\Controller;
use App\Models\Fabric\FilterOperator;
use Illuminate\Auth\Access\AuthorizationException;

class AdminFilterFieldOperatorController extends Controller
{
    /**
     * Add a role to a user.
     *
     * @param FilterField $filterField
     * @param FilterOperator $operator
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function update(FilterField $filterField, FilterOperator $operator): JsonResponse
    {
        $this->authorize('update', $filterField);

        $filterField->filterOperator()->syncWithoutDetaching($operator);

        return response()->json([
            'message' => 'Operator assigned to filter field successfully.'
        ]);
    }

    /**
     * Remove a role from a user
     *
     * @param FilterField $filterField
     * @param FilterOperator $operator
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(FilterField $filterField, FilterOperator $operator): JsonResponse
    {
        $this->authorize('delete', $filterField);

        $filterField->filterOperator()->detach($operator);

        return response()->json([
            'message' => 'Operator removed from filter field successfully.'
        ]);
    }
}
