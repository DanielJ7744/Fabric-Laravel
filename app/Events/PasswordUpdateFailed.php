<?php

namespace App\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasswordUpdateFailed
{
    use Dispatchable, SerializesModels;

    /**
     * The user that attempted to update their password.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public Authenticatable $user;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }
}
