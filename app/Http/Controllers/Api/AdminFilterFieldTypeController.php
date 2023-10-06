<?php

namespace App\Http\Controllers\Api;

use App\Models\Fabric\FilterType;
use Illuminate\Http\JsonResponse;
use App\Models\Fabric\FilterField;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class AdminFilterFieldTypeController extends Controller
{
    /**
     * Add a filter type to a filter field.
     *
     * @param FilterField $filterField
     * @param FilterType $type
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function update(FilterField $filterField, FilterType $type): JsonResponse
    {
        $this->authorize('update', $filterField);

        $filterField->filterType()->syncWithoutDetaching($type);

        return response()->json([
            'message' => 'Type assigned to filter field successfully.'
        ]);
    }

    /**
     * Remove a filter type from a filter field
     *
     * @param FilterField $filterField
     * @param FilterType $type
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(FilterField $filterField, FilterType $type): JsonResponse
    {
        $this->authorize('delete', $filterField);

        $filterField->filterType()->detach($type);

        return response()->json([
            'message' => 'Type removed from filter field successfully.'
        ]);
    }
}
