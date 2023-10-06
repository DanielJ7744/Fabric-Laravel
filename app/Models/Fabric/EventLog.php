<?php

namespace App\Models\Fabric;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Models\Audit;

class EventLog extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'company_id' => 'integer',
        'successful' => 'boolean',
    ];

    /**
     * The different areas that can be logged.
     *
     * @var array
     */
    public static $areas = [
        'account',
        'service',
        'security',
        'integration',
        'connector',
        'authentication',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->ip_address) {
                $model->ip_address = request()->ip();
            }
        });
    }

    /**
     * Get the user for the event log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => '[unavailable]',
            'email' => '[unavailable]',
        ]);
    }

    /**
     * Get the company for the event log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the audit for the event log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * Get the model for the event log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to search for the given term.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term): Builder
    {
        return $query->where('value',  'like', "%{$term}%")
            ->orWhere('ip_address', 'like', "{$term}%")
            ->orWhereHas('audit', fn ($audit) => $audit
                ->where('new_values', 'like', "%{$term}%")
                ->orWhere('old_values', 'like', "%{$term}%"))
            ->orWhereHas('user', fn ($user) => $user
                ->where('email', 'like', "{$term}%"));
    }

    /**
     * Set the associated user, and company if present.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @return self
     */
    public function setUser(?Authenticatable $user): self
    {
        $this->user()->associate($user);
        $this->company()->associate(optional($user)->company);

        return $this;
    }

    /**
     * Set the area for the action taken.
     *
     * @return string $action
     * @return self
     */
    public function setArea(string $area): self
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Set the action for the event log.
     *
     * @return string $action
     * @return self
     */
    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Set a value for the event log.
     *
     * @return mixed $action
     * @return self
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Set the method for the event log.
     *
     * @return string $method
     * @return self
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Set the event log status.
     *
     * @param bool $bool
     * @return self
     */
    public function setSuccessful($bool = true): self
    {
        $this->successful = $bool;

        return $this;
    }

    /**
     * Set the event log as a fail.
     *
     * @return self
     */
    public function setFailed(): self
    {
        $this->successful = false;

        return $this;
    }
}
