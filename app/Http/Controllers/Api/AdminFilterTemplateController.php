<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Fabric\FilterTemplate;
use App\Http\Resources\FilterTemplateResource;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Api\AdminStoreFilterTemplateRequest;
use App\Http\Requests\Api\AdminUpdateFilterTemplateRequest;

class AdminFilterTemplateController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param AdminStoreFilterTemplateRequest $request
     *
     * @return FilterTemplateResource
     *
     * @throws AuthorizationException
     */
    public function store(AdminStoreFilterTemplateRequest $request): FilterTemplateResource
    {
        $this->authorize('create', FilterTemplate::class);

        $filterTemplate = FilterTemplate::create($request->validated());

        return new FilterTemplateResource($filterTemplate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUpdateFilterTemplateRequest $request
     * @param FilterTemplate $filterTemplate
     *
     * @return FilterTemplateResource
     *
     * @throws AuthorizationException
     */
    public function update(AdminUpdateFilterTemplateRequest $request, FilterTemplate $filterTemplate): FilterTemplateResource
    {
        $this->authorize('update', $filterTemplate);

        $filterTemplate->update($request->validated());

        return new FilterTemplateResource($filterTemplate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FilterTemplate $filterTemplate
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(FilterTemplate $filterTemplate): JsonResponse
    {
        $this->authorize('delete', $filterTemplate);

        $filterTemplate->delete();

        return response()->json([
            'message' => 'Filter template deleted successfully.'
        ]);
    }
}
