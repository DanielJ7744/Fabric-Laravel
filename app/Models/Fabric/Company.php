<?php

namespace App\Models\Fabric;

use App\Facades\Hasura;
use App\Models\Alerting\AlertGroups;
use App\Models\Tapestry\Connector;
use App\Models\Tapestry\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Multitenancy\Models\Tenant;

class Company extends Tenant implements Auditable
{
    use IsAuditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'active',
        'trial_ends_at',
        'company_website',
        'company_phone',
        'company_email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'trial_ends_at',
    ];

    /**
     * Get the integrations for the company.
     *
     * @return HasMany
     */
    public function integrations(): HasMany
    {
        return $this->hasMany(Integration::class);
    }

    /**
     * Get the users for the company.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the event logs for the company.
     *
     * @return HasMany
     */
    public function eventLogs(): HasMany
    {
        return $this->hasMany(EventLog::class);
    }

    /**
     * Get the alert groups for the company.
     *
     * @return HasMany
     */
    public function alertGroups(): HasMany
    {
        return $this->hasMany(AlertGroups::class);
    }

    /**
     * Get the subscriptions for the company.
     *
     * @return BelongsToMany
     */
    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Subscription::class)->withTimestamps();
    }

    /**
     * Scope a query to company is on a trial.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTrialExpired($query): Builder
    {
        return $query->whereNotNull('trial_ends_at')->where('trial_ends_at', '<', now());
    }

    /**
     * Retrieve the company's integration's usernames.
     *
     * @return Collection
     */
    public function getIntegrationUsernames(): Collection
    {
        return Cache::remember($this->getKey() . '.integrations.usernames', 0, fn () => $this->integrations()->pluck('username'));
    }

    /**
     * Retrieve the company's integration's IDs.
     *
     * @return Collection
     */
    public function getIntegrationIds(): Collection
    {
        return Cache::remember($this->getKey() . '.integrations.ids', 0, fn () => $this->integrations()->pluck('id'));
    }

    /**
     * Retrieve the subscription allowance for the company.
     *
     * @return Object
     */
    public function subscriptionAllowance(): Object
    {
        return (object) [
            'users'             => $this->subscriptions->sum('users'),
            'price'             => $this->subscriptions->sum('price'),
            'services'          => $this->subscriptions->sum('services'),
            'api_keys'          => $this->subscriptions->sum('api_keys'),
            'transactions'      => $this->subscriptions->sum('transactions'),
            'sftp'              => $this->subscriptions->contains(fn ($subscription) => $subscription->sftp),
            'business_insights' => $this->subscriptions->contains(fn ($subscription) => $subscription->business_insights),
            'tiers'             => $this->subscriptions->pluck('name'),
        ];
    }

    /**
     * Retrieve the subscription usage for the company.
     *
     * @return Object
     * @throws \ErrorException
     */
    public function subscriptionUsage(): Object
    {
        $usernames = $this->getIntegrationUsernames();

        try {
            $transactions = Cache::remember(
                sprintf('%ss_transaction_usage', $usernames->implode('_')),
                now()->addMinutes(5),
                fn () => Hasura::transactions($usernames, now()->startOfMonth(), now())
            );

            $transactionCount = $transactions->sum('total_transactions');
            $totalPullDataSize = $transactions->sum('total_pull_data_size');
        } catch (\Throwable $th) {
            [$transactionCount,  $totalPullDataSize] = [0, 0];
        }

        return (object) [
            'transaction_count'    => $transactionCount,
            'total_pull_data_size' => $totalPullDataSize,
            'active_users'         => $this->users()->count(),
            'active_services'      => Service::usernames($usernames)->billable()->active()->count(),
            'inbound_apis'         => $this->integrations->sum(fn ($integration) => (new Connector())->setIdxTable($integration->username)->inboundApis()->count())
        ];
    }

    /**
     * Determine if the company's trial has expired.
     *
     * @return bool
     */
    public function trialExpired(): bool
    {
        return !is_null($this->trial_ends_at) && $this->trial_ends_at->isPast();
    }
}
