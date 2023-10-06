<?php

namespace App\Models\Alerting;

use App\Models\Fabric\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class AlertRecipients extends Model implements Auditable
{
    use IsAuditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alert_recipients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'group_id',
        'name',
        'user_id',
        'email'
    ];

    /**
     * Get the alert group for the alert recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(AlertGroups::class);
    }

    /**
     * Get the alert service recipient for the alert recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function service(): HasOne
    {
        return $this->HasOne(AlertServiceRecipients::class, 'service_id', 'id');
    }

    /**
     * Get the alert service recipients for the alert recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function recipients(): HasOne
    {
        return $this->HasOne(AlertServiceRecipients::class, 'recipient_id', 'id');
    }

    /**
     * Get the user for the alert recipient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
