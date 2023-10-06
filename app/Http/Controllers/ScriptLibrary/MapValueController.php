<?php

namespace App\Http\Controllers\ScriptLibrary;

use App\Http\Controllers\GatewayController;
use App\Http\Helpers\ScriptLibrary\MapHelper;
use App\Http\Interfaces\BatchRedirectInterface;
use App\Http\Requests\ScriptLibrary\MapValue\DeleteRequest;
use App\Http\Requests\ScriptLibrary\MapValue\StoreRequest;
use App\Http\Requests\ScriptLibrary\MapValue\UpdateRequest;
use Psr\Http\Message\ResponseInterface;

class MapValueController extends GatewayController implements BatchRedirectInterface
{
    use MapHelper;

    private string $baseUri = 'maps/%s/values';

    public function store(StoreRequest $request, int $mapId): ResponseInterface
    {
        return $this->forward(
            $request,
            'POST',
            self::getScriptLibraryUri(sprintf($this->baseUri, $mapId)),
            [],
            $request->input()
        );
    }

    public function update(UpdateRequest $request, int $mapId, int $valueId): ResponseInterface
    {
        return $this->forward(
            $request,
            'PATCH',
            self::getScriptLibraryUri(sprintf($this->baseUri . '/%s', $mapId, $valueId)),
            [],
            $request->input()
        );
    }

    public function destroy(DeleteRequest $request, int $mapId, int $valueId): ResponseInterface
    {
        return $this->forward(
            $request,
            'DELETE',
            self::getScriptLibraryUri(sprintf($this->baseUri . '/%s', $mapId, $valueId)),
        );
    }
}
