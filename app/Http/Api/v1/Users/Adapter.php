<?php

namespace App\Http\Api\v1\Users;

use App\Models\Fabric\User;
use Closure;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Eloquent\BelongsTo;
use CloudCreativity\LaravelJsonApi\Eloquent\Concerns\SoftDeletesModels;
use CloudCreativity\LaravelJsonApi\Eloquent\HasMany;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Adapter extends AbstractAdapter
{
    use SoftDeletesModels;

    protected $softDeleteField = 'deleted_at';

    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Mapping of JSON API filter names to model scopes.
     *
     * @var array
     */
    protected $filterScopes = [];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new User(), $paging);
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     *
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        $hasSearchUsersPermission = Auth::user()->hasPermissionTo(Permission::where('name', 'search users')->first());
        if (!$hasSearchUsersPermission && Auth::user()->company) {
            $query->where('company_id', Auth::user()->company->id);
        }

        $deletedOnly = $filters->get('include_deleted_users');
        if (is_string($deletedOnly)) {
            //remove filter to prevent failures
            $filters->forget('include_deleted_users');
        }

        if ($deletedOnly === 'true') {
            $query->withTrashed();
        }

        $this->filterWithScopes($query, $filters);
    }

    protected function roles(): HasMany
    {
        return $this->hasMany();
    }

    protected function company(): BelongsTo
    {
        return $this->belongsTo();
    }

    protected function integrations(): HasMany
    {
        return $this->hasMany();
    }

    protected function updating(User $user, ResourceObject $resource): void
    {
        if ($resource->getAttributes()->has('password')) {
            abort(Response::HTTP_NOT_ACCEPTABLE, 'Passwords must be updated via the /my/password endpoint.');
        }
    }

    protected function deleting(User $user): void
    {
        //
    }
}
