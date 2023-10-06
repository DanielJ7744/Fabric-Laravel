<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ElasticsearchHelper;
use App\Http\Helpers\ReportSyncHelper;
use App\Http\Requests\Api\IndexReportSyncsRequest;
use App\Models\Tapestry\SyncReport;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SyncReportsController extends Controller
{
    public function __construct(ReportSyncHelper $reportSyncHelper, ElasticsearchHelper $elasticsearchHelper)
    {
        $this->reportSyncHelper = $reportSyncHelper;
        $this->elasticsearchHelper = $elasticsearchHelper;
    }

    /**
     * Get sync reports
     *
     * @param IndexReportSyncsRequest $request
     *
     * @return AnonymousResourceCollection|StreamedResponse
     */
    public function index(IndexReportSyncsRequest $request)
    {
        return SyncReport::getSyncReports(Auth::user(), $request, $this->reportSyncHelper, $this->elasticsearchHelper);
    }
}
