<?php

namespace App\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasswordResetFailed
{
    use Dispatchable, SerializesModels;

    /**
     * The user that attempted a password reset.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public Authenticatable $user;

    /**
     * The token used to attempt the password reset.
     *
     * @var string $token
     */
    public string $token;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param string  $token
     * @return void
     */
    public function __construct(Authenticatable $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }
}
