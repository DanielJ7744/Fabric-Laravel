<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\LaravelTestCase;

/**
 * @group users
 */
class PasswordControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->make());
    }

    public function test_users_can_request_a_password_reset_email(): void
    {
        Notification::fake();

        $this
            ->postJson(route('api.password.email'), ['email' => $this->user->email])
            ->assertOk();

        Notification::assertSentTo($this->user, ResetPasswordNotification::class);
    }

    public function test_users_with_matching_email_are_not_informed_of_success(): void
    {
        Notification::fake();

        $this
            ->postJson(route('api.password.email'), ['email' => $this->user->email])
            ->assertJsonPath('message', 'Please check your email for a password reset link.');
    }

    public function test_users_without_matching_email_are_not_informed_of_success(): void
    {
        Notification::fake();

        $this
            ->postJson(route('api.password.email'), ['email' => 'made@up.com'])
            ->assertJsonPath('message', 'Please check your email for a password reset link.');
    }

    public function test_users_with_valid_token_can_reset_their_password(): void
    {
        $passwordResetToken = Password::getRepository()->create($this->user);
        $password = 'IamPassword123!';

        $this
            ->postJson(route('api.password.update'), [
                'email' => $this->user->email,
                'token' => $passwordResetToken,
                'password' => $password,
                'password_confirmation' => $password,
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Password Set Successfully.');
    }

    public function test_users_with_invalid_token_cannot_reset_their_password(): void
    {
        $password = 'IamPassword123!';

        $this
            ->postJson(route('api.password.update'), [
                'email' => $this->user->email,
                'token' => 'wrong',
                'password' => $password,
                'password_confirmation' => $password,
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('message', 'Error setting password. Please try with a fresh reset token.');
    }
}
