<?php

namespace App\Http\Controllers\ScriptLibrary;

use App\Http\Controllers\GatewayController;
use App\Http\Helpers\ScriptLibrary\MapHelper;
use App\Http\Interfaces\BatchRedirectInterface;
use App\Http\Requests\ScriptLibrary\Map\DeleteRequest;
use App\Http\Requests\ScriptLibrary\Map\IndexRequest;
use App\Http\Requests\ScriptLibrary\Map\ShowRequest;
use App\Http\Requests\ScriptLibrary\Map\StoreRequest;
use App\Http\Requests\ScriptLibrary\Map\UpdateRequest;
use Psr\Http\Message\ResponseInterface;

class MapController extends GatewayController implements BatchRedirectInterface
{
    use MapHelper;

    public function index(IndexRequest $request): ResponseInterface
    {
        return $this->forward(
            $request,
            'GET',
            self::getScriptLibraryUri('maps'),
            $this->jsonHeaders
        );
    }

    public function store(StoreRequest $request): ResponseInterface
    {
        return $this->forward(
            $request,
            'POST',
            self::getScriptLibraryUri('maps'),
            [],
            $request->input()
        );
    }

    public function show(ShowRequest $request, int $mapId): ResponseInterface
    {
        return $this->forward(
            $request,
            'GET',
            self::getScriptLibraryUri(sprintf('maps/%s', $mapId)),
            $this->jsonHeaders
        );
    }

    public function update(UpdateRequest $request, int $mapId): ResponseInterface
    {
        return $this->forward(
            $request,
            'PATCH',
            self::getScriptLibraryUri(sprintf('maps/%s', $mapId)),
            [],
            $request->input()
        );
    }

    public function destroy(DeleteRequest $request, int $mapId): ResponseInterface
    {
        return $this->forward(
            $request,
            'DELETE',
            self::getScriptLibraryUri(sprintf('maps/%s', $mapId))
        );
    }
}
