<?php

namespace App\Models\Fabric;

use App\Events\PasswordResetRequested;
use App\Http\Interfaces\EventLogInterface;
use App\Models\Fabric\SocialAccount;
use App\Notifications\NewUserAccountNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Laravel\Socialite\Two\User as SocialiteUser;
use OwenIt\Auditing\Auditable as IsAuditable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable, EventLogInterface
{
    use Notifiable, HasRoles, HasApiTokens, IsAuditable, SoftDeletes;

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'id',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'telephone', 'mobile', 'avatar_url', 'company_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if (is_null($user->password)) {
                $user->password = Str::random(8);
            }
        });
    }

    /**
     * Get the company for the user.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the integrations for a user
     *
     * @return BelongsToMany
     */
    public function integrations(): BelongsToMany
    {
        return $this->belongsToMany(Integration::class)->withTimestamps();
    }

    /**
     * Get the social accounts for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Encrypt the user's password.
     *
     * @param string  $value
     * @return void
     */
    public function setPasswordAttribute(?string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Send the password reset notification
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));

        event(new PasswordResetRequested($this));
    }

    /**
     * Send the new account notification
     *
     * @param string $token
     * @return void
     */
    public function sendNewAccountNotification($token): void
    {
        $this->notify(new NewUserAccountNotification($token));
    }

    /**
     * Determine whether the user is a Patchworks user.
     *
     * @return bool
     */
    public function isPatchworksUser(): bool
    {
        return $this->roles->contains('name', 'patchworks admin') or
            $this->roles->contains('name', 'patchworks user');
    }

    public function isPatchworksAdmin(): bool
    {
        return $this->roles->contains('name', 'patchworks admin');
    }

    /**
     * Attach a social account for the user.
     *
     * @param string $provider
     * @param \Laravel\Socialite\Two\User $account
     * @return \App\Models\Fabric\SocialAccount
     */
    public function addSocialAccount(string $provider, SocialiteUser $account): SocialAccount
    {
        if ($avatarUrl = $account->getAvatar()) {
            $this->update(['avatar_url' => $avatarUrl]);
        }

        $account = $this->socialAccounts()->updateOrCreate(
            ['provider' => $provider],
            ['provider_user_id' => $account->getId()]
        );

        return $account;
    }

    public static function getArea(): string
    {
        return 'security';
    }

    public function scopeSearch($query, $term)
    {
        if (strlen($term) > 3) {
            return $query
                ->where('name', 'LIKE', '%' . $term . '%')
                ->orWhere('email', 'LIKE', '%' . $term . '%')
                ->orWhereHas('company', function ($q) use ($term) {
                    $q->where('name', 'LIKE', '%' . $term . '%');
                });
        }

        return $query;
    }
}
