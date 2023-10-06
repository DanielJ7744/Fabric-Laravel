<?php

namespace App\Models\Alerting;

use App\Models\Fabric\Company;
use App\Models\Fabric\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class AlertManager extends Model implements Auditable
{
    use IsAuditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alert_manager';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'service_id',
        'config_id',
        'recipient_id',
        'alert_type',
        'send_from',
        'dispatched_at',
        'failed_at',
        'seen_on_dashboard',
        'service_log_run_ids',
    ];

    /**
     * Get the company for the alert manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the alert configs for the alert manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function configs(): HasMany
    {
        return $this->hasMany(AlertConfigs::class,  'id', 'config_id');
    }

    /**
     * Get the services for the alert manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function service(): HasMany
    {
        return $this->hasMany(Service::class, 'service_id', 'id');
    }

    /**
     * Get the recipients for the alert manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(AlertRecipients::class, 'id', 'recipient_id');
    }
}
