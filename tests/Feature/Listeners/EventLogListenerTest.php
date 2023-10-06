<?php

namespace Tests\Feature\Listeners;

use App\Events\PasswordResetFailed;
use App\Events\PasswordResetRequested;
use App\Events\ServiceScheduleFailed;
use App\Events\ServiceScheduled;
use App\Models\Fabric\User;
use Exception;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventLogListenerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_login_event_creates_event_log(): void
    {
        $this->passportAs($user = factory(User::class)->create());

        event(new Login('web', $user, false));

        $this->assertDatabaseHas('event_logs', [
            'user_id' => $user->getKey(),
            'area' => 'authentication',
            'action' => 'login',
            'successful' => true,
        ]);
    }

    public function test_logout_event_creates_event_log(): void
    {
        $this->passportAs($user = factory(User::class)->create());

        event(new Logout('web', $user, false));

        $this->assertDatabaseHas('event_logs', [
            'user_id' => $user->getKey(),
            'area' => 'authentication',
            'action' => 'logout',
            'successful' => true,
        ]);
    }

    public function test_failed_login_event_with_user_creates_event_log(): void
    {
        $user = factory(User::class)->create(['email' => 'adam@patchworks.com']);

        event(new Failed('web', $user, ['email' => 'adam@patchworks.com', 'password' => 'wrong']));

        $this->assertDatabaseHas('event_logs', [
            'user_id' => $user->getKey(),
            'area' => 'authentication',
            'action' => 'login',
            'successful' => false,
            'value' => null,
        ]);
    }

    public function test_failed_login_event_without_user_creates_event_log(): void
    {
        event(new Failed('web', null, ['email' => 'adam@patchworks.com', 'password' => 'wrong']));

        $this->assertDatabaseHas('event_logs', [
            'user_id' => null,
            'area' => 'authentication',
            'action' => 'login',
            'successful' => false,
            'value' => 'adam@patchworks.com',
        ]);
    }

    public function test_password_reset_event_creates_event_log(): void
    {
        event(new PasswordReset($user = factory(User::class)->create()));

        $this->assertDatabaseHas('event_logs', [
            'user_id' => $user->getKey(),
            'area' => 'security',
            'action' => 'password_reset',
            'successful' => true,
        ]);
    }

    public function test_password_reset_event_log(): void
    {
        event(new PasswordResetRequested($user = factory(User::class)->create()));

        $this->assertDatabaseHas('event_logs', [
            'user_id' => $user->getKey(),
            'area' => 'security',
            'action' => 'password_reset_requested',
            'successful' => true,
        ]);
    }

    public function test_password_reset_failed_event_log(): void
    {
        event(new PasswordResetFailed($user = factory(User::class)->create(), 123));

        $this->assertDatabaseHas('event_logs', [
            'user_id' => $user->getKey(),
            'area' => 'security',
            'action' => 'password_reset',
            'successful' => false,
            'value' => 123,
        ]);
    }

    public function test_service_run_event_log(): void
    {
        $this->passportAs($user = factory(User::class)->create());

        $service = [
            'id' => 123,
            'description' => 'test service',
        ];

        event(new ServiceScheduled($service));

        $this->assertDatabaseHas('event_logs', [
            'user_id' => $user->getKey(),
            'area' => 'service',
            'action' => 'service_scheduled_manually',
            'successful' => true,
            'value' => '123 / test service',
        ]);
    }

    public function test_service_run_failed_event_log(): void
    {
        $this->passportAs($user = factory(User::class)->create());

        $service = [
            'id' => 123,
            'description' => 'test service',
        ];

        event(new ServiceScheduleFailed($service, new Exception));

        $this->assertDatabaseHas('event_logs', [
            'user_id' => $user->getKey(),
            'area' => 'service',
            'action' => 'service_scheduled_manually',
            'successful' => false,
            'value' => '123 / test service',
        ]);
    }

    public function test_successful_login_logs_event(): void
    {
        $user = factory(User::class)->create(['email' => 'adam@patchworks.co.uk', 'password' => 'right']);

        $this->postJson(url('/api/v1/login'), [
            'password' => 'right',
            'email' => 'adam@patchworks.co.uk',
        ]);

        $this->assertDatabaseHas('event_logs', [
            'value' => null,
            'action' => 'login',
            'successful' => true,
            'area' => 'authentication',
            'user_id' => $user->getKey(),
        ]);
    }

    public function test_unsuccessful_login_with_with_existing_user_logs_event(): void
    {
        $user = factory(User::class)->create(['email' => 'adam@patchworks.co.uk', 'password' => 'right']);

        $this->postJson(url('/api/v1/login'), [
            'password' => 'wrong',
            'email' => 'adam@patchworks.co.uk',
        ]);

        $this->assertDatabaseHas('event_logs', [
            'value' => null,
            'action' => 'login',
            'successful' => false,
            'area' => 'authentication',
            'user_id' => $user->getKey(),
        ]);
    }

    public function test_unsuccessful_login_with_without_existing_user_logs_event(): void
    {
        $this->postJson(url('/api/v1/login'), [
            'password' => 'wrong',
            'email' => 'adam@patchworks.co.uk',
        ]);

        $this->assertDatabaseHas('event_logs', [
            'user_id' => null,
            'action' => 'login',
            'successful' => false,
            'area' => 'authentication',
            'value' => 'adam@patchworks.co.uk',
        ]);
    }

    public function test_successful_password_update_logs_event(): void
    {
        $this
            ->passportAs($user = factory(User::class)->create(['password' => 'right']))
            ->putJson(route('api.v2.my.password.update'), [
                'current_password' => 'right',
                'password' => 'Password1!',
                'password_confirmation' => 'Password1!',
            ]);

        $this->assertDatabaseHas('event_logs', [
            'user_id' => $user->getKey(),
            'action' => 'password_updated',
            'successful' => true,
            'area' => 'security',
            'value' => null,
        ]);
    }

    public function test_unsuccessful_password_update_logs_event(): void
    {
        $this
            ->passportAs($user = factory(User::class)->create(['password' => 'right']))
            ->putJson(route('api.v2.my.password.update'), [
                'current_password' => 'wrong',
                'password' => 'Password1!',
                'password_confirmation' => 'Password1!',
            ]);

        $this->assertDatabaseHas('event_logs', [
            'user_id' => $user->getKey(),
            'action' => 'password_updated',
            'successful' => false,
            'area' => 'security',
            'value' => null,
        ]);
    }
}
