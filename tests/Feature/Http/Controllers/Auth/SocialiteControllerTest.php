<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\Fabric\Company;
use App\Models\Fabric\SocialAccount;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\LaravelTestCase;

/**
 * @group auth
 * @group socialite
 */
class SocialiteControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->user = $this->company->users()->save(factory(User::class)->make([
            'email' => 'socialite@patchworks.com'
        ]));
    }

    public function test_users_can_are_redirected_to_google(): void
    {
        $this->passportAs($this->user);

        Socialite::shouldReceive('driver->stateless->redirect')
            ->andReturn(new RedirectResponse('https://patchworks.com'));

        collect(SocialAccount::$providers)->each(function ($provider) {
            $this->get(route('sso.provider', $provider))->assertRedirect();
        });
    }

    public function test_users_can_login_with_google(): void
    {
        $this->passportAs($this->user);

        $account = new SocialiteUser;
        $account->id = 1;
        $account->email = 'socialite@patchworks.com';
        $account->user['email_verified'] = true;

        Socialite::shouldReceive('driver->stateless->user')->andReturn($account);

        collect(SocialAccount::$providers)->each(function ($provider) {
            $this->get(route('sso.callback', $provider))->assertRedirect();

            $this->assertTrue($this->user->socialAccounts()->where('provider', $provider)->exists());
        });
    }
}
