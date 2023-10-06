<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Alerting\AlertServiceRecipients;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use CloudCreativity\LaravelJsonApi\Http\Requests\FetchResource;
use Illuminate\Http\Response;

class AlertServiceRecipientsController extends JsonApiController
{
    /**
     * Delete alert service recipients by service ID
     *
     * @param FetchResource $request
     *
     * @return Response
     */
    public function deleteAlertServiceRecipientByServiceId(FetchResource $request): Response
    {
        $requestData = $request->all();
        if (!$this->isRequiredDataSet($requestData)) {
            return response('Required data not set', 400);
        }

        try {
            $existingRecords = AlertServiceRecipients::whereIn('service_id', $requestData['service_ids']);
            if (isset($requestData['group_ids'])) {
                $existingRecords->whereIn('group_id', $requestData['group_ids'])->delete();
            }

            if (isset($requestData['recipient_ids'])) {
                $existingRecords->whereIn('recipient_id', $requestData['recipient_ids'])->delete();
            }

            return response('Delete success', 204);
        } catch (Exception $exception) {
            return response($exception->getMessage(), 500);
        }
    }

    /**
     * Is the required data set?
     *
     * @param array $requestData
     *
     * @return bool
     */
    protected function isRequiredDataSet(array $requestData): bool
    {
        return isset($requestData['delete'], $requestData['service_ids'])
            && $requestData['delete'] === true
            && is_array($requestData['service_ids'])
            && !empty($requestData['service_ids']);
    }
}
