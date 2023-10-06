<?php

namespace App\Models\Alerting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * This is technically related to the service model sitting in tapestry by service_id
 * This is a one to one relationship with one service recipient being linked to one service
 */
class AlertServiceRecipients extends Model implements Auditable
{
    use IsAuditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alert_service_recipients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['service_id', 'recipient_id', 'group_id'];

    /**
     * Get the alert recipients for the alert service recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(AlertRecipients::class, 'id', 'recipient_id');
    }

    /**
     * Get the alert group for the alert service recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(AlertGroups::class, 'group_id', 'id');
    }
}
