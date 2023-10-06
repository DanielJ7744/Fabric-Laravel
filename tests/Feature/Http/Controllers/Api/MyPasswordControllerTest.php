<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Events\PasswordUpdateFailed;
use App\Events\PasswordUpdated;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\LaravelTestCase;

class MyPasswordControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['password' => 'right']);
    }

    public function test_users_can_update_their_password(): void
    {
        $this->expectsEvents(PasswordUpdated::class);

        $this
            ->passportAs($this->user)
            ->putJson(route('api.v2.my.password.update'), [
                'current_password' => 'right',
                'password' => 'Password1!',
                'password_confirmation' => 'Password1!',
            ])
            ->assertOk();

        $this->assertTrue(Hash::check('Password1!', $this->user->password));
    }

    public function test_users_must_confirm_current_password_to_update_their_password(): void
    {
        $this->expectsEvents(PasswordUpdateFailed::class);

        $this
            ->passportAs($this->user)
            ->putJson(route('api.v2.my.password.update'), [
                'current_password' => 'wrong',
                'password' => 'Password1!',
                'password_confirmation' => 'Password1!',
            ])
            ->assertForbidden();
    }
}
