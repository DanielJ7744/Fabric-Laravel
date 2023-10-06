<?php

namespace App\Models\Tapestry;

use App\Http\Helpers\ElasticsearchHelper;
use App\Http\Helpers\ReportSyncHelper;
use App\Http\Requests\Api\IndexReportSyncsRequest;
use App\Http\Resources\Tapestry\SyncReportResource;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SyncReport extends TapestryModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the integrations for the sync.
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class, 'company_username', 'username');
    }

    public static function getSyncReports($authUser, $request, ReportSyncHelper $reportSyncHelper, ElasticsearchHelper $elasticsearchHelper)
    {
        $query = new self();
        $filters = $request->filter;
        $integrations = self::getIntegrationsFromRequest($authUser);

        foreach ($integrations as $key => $integration) {
            if ($key === 0) {
                $query->setTable(sprintf('idx_%s', $integration->username));
                $query = self::addSelect($query, $integration);
                $query = self::addFilters($query, $filters)->with('integration');
            } else {
                $union = (new self)->setTable(sprintf('idx_%s', $integration->username));
                $union = self::addSelect($union, $integration);
                $query = $query->union(self::addFilters($union, $filters)->with('integration'));
            }
        }

        if ($filters && array_key_exists('sort_field', $filters) && !is_null($filters['sort_field'])) {
            $direction = $filters['sort_direction'] ?: 'desc';
            $query->orderBy($filters['sort_field'], $direction);
        }

        if ($request->exportCsv) {
            return self::exportCsv($query);
        }

        $queryResults = $query->paginate();
        $results = $queryResults->map(function ($item) use ($integrations, $reportSyncHelper, $elasticsearchHelper) {
            $itemIntegration = $integrations->first(fn ($integration) => $integration->username === $item->company_username);
            $item->resync_values = $reportSyncHelper->getEntityResyncData($itemIntegration, $item->toArray(), $elasticsearchHelper);

            return $item;
        });

        return SyncReportResource::collection($results)->additional(['meta' => [
            'available_entities' => self::getAvailableEntitiesForIntegrations($integrations),
            'total' => $query->count()
        ]]);
    }

    /**
     * Get the available entities
     *
     * @return array
     */
    public function getAvailableEntities(): array
    {
        return $this->distinct()->type('all')->pluck('type')->toArray();
    }

    /**
     * Get the available entites for an integration
     *
     * @param Collection $integrations
     *
     * @return array
     */
    public static function getAvailableEntitiesForIntegrations(Collection $integrations): array
    {
        $availableEntities = [];
        foreach ($integrations as $integration) {
            $syncReports = new self();
            $syncReports->setTable(sprintf('idx_%s', $integration->username));
            $availableEntities = array_unique(array_merge($availableEntities, $syncReports->getAvailableEntities()));
        }

        return $availableEntities;
    }

    /**
     * Add the select to the query
     *
     * @param SyncReport $query
     * @param Integration $integration
     *
     * @return Builder
     */
    public static function addSelect(SyncReport $query, Integration $integration): Builder
    {
        return $query->select([
            'id',
            'type',
            'system_chain',
            'common_ref',
            'source_id',
            'status',
            'first_run_id',
            'last_run_id',
            'created_at',
            'updated_at',
            'message',
            'first_service_id',
            DB::raw(sprintf('\'%s\' as company_username', $integration->username))
        ]);
    }

    /**
     * Add filters to the query builder
     *
     * @param Builder $query
     * @param array|null $filters
     *
     * @return Builder
     */
    public static function addFilters(Builder $query, ?array $filters): Builder
    {
        if (!$filters) {
            return $query;
        }
        if (array_key_exists('start', $filters) && array_key_exists('end', $filters)) {
            $query = $query->createdBetween(
                self::getDateTimeInDBFormat($filters['start']),
                self::getDateTimeInDBFormat($filters['end'])
            );
        }
        if (array_key_exists('days', $filters) && !is_null($filters['days'])) {
            $query = $query->days($filters['days']);
        }
        if (array_key_exists('status', $filters) && !is_null($filters['status'])) {
            $query = $query->status($filters['status']);
        }
        if (array_key_exists('type', $filters) && !is_null($filters['type'])) {
            $query = $query->type($filters['type']);
        }
        if (array_key_exists('system_chain', $filters) && !is_null($filters['system_chain'])) {
            $query = $query->systemChain($filters['system_chain']);
        }
        if (array_key_exists('search_term', $filters) && !is_null($filters['search_term'])) {
            $query = $query->searchTerm($filters['search_term']);
        }

        return $query;
    }

    /**
     * Get the time in the DB format
     *
     * @param string $dateToFormat
     *
     * @return string
     */
    public static function getDateTimeInDBFormat(string $dateToFormat): string
    {
        return Carbon::parse($dateToFormat)->format('Y-m-d H:i:s');
    }

    /**
     * Get integratons from the request
     *
     * @param User $authUser
     *
     * @return Collection
     */
    public static function getIntegrationsFromRequest(User $authUser): Collection
    {
        $filters = request()->filter;
        if ($filters && array_key_exists('integrations', $filters) && !is_null($filters['integrations'])) {
            $integrations = $authUser->company->integrations()->whereIn('id', explode(',', request()->filter['integrations']))->get();
        } else {
            $integrations = $authUser->company->integrations()->get();
        }

        return $integrations;
    }

    /**
     * Export sync reports as a CSV
     *
     * @param Builder $query
     *
     * @return StreamedResponse
     */
    public static function exportCsv(Builder $query): StreamedResponse
    {
        $fileName = 'Sync Reports.csv';
        $syncReports = $query->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => sprintf('attachment; filename=%s', $fileName)
        ];

        $columns = ['Company Group', 'Service Entity', 'Systems', 'Sync ID', 'Reference', 'Date', 'Status'];

        $callback = function () use ($syncReports, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($syncReports as $syncReport) {
                $row['Company Group'] = $syncReport->integration->name;
                $row['Service Entity'] = $syncReport->type;
                $row['Systems'] = strtr($syncReport->system_chain, ['_' => ' > ']);
                $row['Sync ID'] = $syncReport->last_run_id;
                $row['Reference'] = $syncReport->common_ref;
                $row['Date'] = Carbon::createFromFormat('Y-m-d H:i:s', $syncReport->created_at)->format('d/m/Y H:i:s');
                $row['Status'] = $syncReport->status;

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    /**
     * Add a where created > x days to the query
     *
     * @param Builder $query
     * @param int $days
     *
     * @return Builder
     */
    public function scopeDays(Builder $query, int $days): Builder
    {
        return $query->where('created_at', '>', Carbon::now()->subDays($days));
    }

    /**
     * Add a where x < created_at < x to the query
     *
     * @param Builder $query
     * @param string $start
     * @param string $end
     *
     * @return Builder
     */
    public function scopeCreatedBetween(Builder $query, string $start, string $end): Builder
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Add a where status = x to the query
     *
     * @param Builder $query
     * @param string $status
     *
     * @return Builder
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Add a where type = x to the query
     *
     * @param Builder $query
     * @param string $type
     *
     * @return Builder
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        if ($type === 'all') {
            return $query->whereNotIn('type', ['', 'Credentials', 'ShipmentMap', 'PaymentMap', 'CustomMap', 'Shopconfig']);
        } else {
            return $query->where('type', $type);
        }
    }

    /**
     * Add a where system_chain = x to the query
     *
     * @param Builder $query
     * @param string $systemChain
     *
     * @return Builder
     */
    public function scopeSystemChain(Builder $query, string $systemChain): Builder
    {
        return $query->where('system_chain', 'LIKE', '%' . $systemChain . '%');
    }

    public function scopeSearchTerm(Builder $query, $term)
    {
        return
            $query
            ->where('last_run_id', 'LIKE', '%' . $term . '%')
            ->orWhere('system_chain', 'LIKE', '%' . $term . '%')
            ->orWhere('common_ref', 'LIKE', '%' . $term . '%');
    }
}
