<?php

namespace App\Models\Tapestry;

use Carbon\Carbon;
use App\Models\Fabric\Integration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceLog extends TapestryModel
{
    /**
     * Disable timestamp usage as Tapestry doesnt use them
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'servicelog';

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the integrations for the service.
     *
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the integrations for the service.
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class, 'username', 'username');
    }

    /**
     * Allow filtering using due at before a date
     *
     * @param Builder $query
     * @param $date
     *
     * @return Builder
     */
    public function scopeDueBefore(Builder $query, $date): Builder
    {
        return $query->where('due_at', '<=', Carbon::parse($date));
    }

    /**
     * Allow filtering using due at after a date
     *
     * @param Builder $query
     * @param $date
     *
     * @return Builder
     */
    public function scopeDueAfter(Builder $query, $date): Builder
    {
        return $query->where('due_at', '>=', Carbon::parse($date));
    }

    /**
     * Allow filtering using started at before a date
     *
     * @param Builder $query
     * @param $date
     *
     * @return Builder
     */
    public function scopeStartedBefore(Builder $query, $date): Builder
    {
        return $query->where('started_at', '<=', Carbon::parse($date));
    }

    /**
     * Allow filtering using started at after a date
     *
     * @param Builder $query
     * @param $date
     *
     * @return Builder
     */
    public function scopeStartedAfter(Builder $query, $date): Builder
    {
        return $query->where('started_at', '>=', Carbon::parse($date));
    }

    /**
     * Allow filtering using finished at before a date
     *
     * @param Builder $query
     * @param $date
     *
     * @return Builder
     */
    public function scopeFinishedBefore(Builder $query, $date): Builder
    {
        return $query->where('finished_at', '<=', Carbon::parse($date));
    }

    /**
     * Allow filtering using finished at after a date
     *
     * @param Builder $query
     * @param $date
     *
     * @return Builder
     */
    public function scopeFinishedAfter(Builder $query, $date): Builder
    {
        return $query->where('finished_at', '>=', Carbon::parse($date));
    }

    /**
     * @param Builder $query
     * @param int $serviceId
     * @param string $date
     * @return Builder
     */
    public function scopeServiceErrors(Builder $query, int $serviceId, string $date): Builder
    {
        return $query->where('requested_by', '!=', 'event')
            ->where('service_id', $serviceId)
            ->where('started_at', '>=', Carbon::createFromTimestamp($date));
    }
}
