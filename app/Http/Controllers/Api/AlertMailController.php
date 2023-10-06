<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AlertMailController extends Controller
{
    /**
     * Send alert mail to a user.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()->isPatchworksUser(), 403, 'You must be a Patchworks user.');

        try {
            Artisan::call('alerts:mail_send', [
                'recipient' => $request->recipient,
                '--name'    => $request->name,
                '--type'    => $request->type,
                '--template' => $request->template,
            ]);

            return response()->json([
                'message' => 'You have successfully sent mail!',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Mail has failed!',
                'error' => $exception->getMessage()
            ]);
        }
    }
}
