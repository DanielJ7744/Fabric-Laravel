<?php

namespace App\Models\Alerting;

use App\Models\Fabric\Company;
use App\Models\Tapestry\Service;
use App\Models\Fabric\FabricModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class AlertConfigs extends FabricModel implements Auditable
{
    use IsAuditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alert_configs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'service_id',
        'throttle_value',
        'error_alert_status',
        'error_alert_threshold',
        'warning_alert_status',
        'warning_alert_threshold',
        'frequency_alert_status',
        'frequency_alert_threshold',
        'alert_frequency',
        'alert_status',
    ];

    /**
     * Get the service for the alert config.
     *
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the company for the alert config.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
