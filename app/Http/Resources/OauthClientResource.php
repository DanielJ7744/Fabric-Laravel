<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Passport\Client;

class OauthClientResource extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @param Client  $resource
     * @param string|null  $secret
     * @return void
     */
    public function __construct(Client $resource, ?string $secret = null)
    {
        parent::__construct($resource);

        $this->secret = $secret;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'redirect' => $this->redirect,
            'revoked' => $this->revoked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'safe_secret' => $this->whenPivotLoaded('connector_oauth_client', fn () => $this->pivot->safe_secret),
            $this->mergeWhen($this->wasRecentlyCreated, ['secret' => $this->secret]),
        ];
    }
}
