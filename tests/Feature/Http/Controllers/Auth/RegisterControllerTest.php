<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Helpers\CompanySettingsHelper;
use App\Models\Fabric\Company;
use App\Models\Fabric\Subscription;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\LaravelTestCase;

class RegisterControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->make();
        $this->company = factory(Company::class)->make();
        factory(Subscription::class)->create(['name' => 'Sandbox']);

        $this->attributes = [
            'terms' => true,
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'company' => [
                'name' => $this->company->name,
                'company_email' => $this->user->email,
                'company_phone' => $this->company->phone_number,
                'company_website' => $this->company->website_url,
            ],
        ];
    }

    public function test_users_can_register_with_a_password(): void
    {
        $password = 'Password1!';
        $attributes = array_merge_recursive($this->attributes, [
            'user' => [
                'password' => $password,
                'password_confirmation' => $password
            ]
        ]);

        $this
            ->postJson(route('api.v2.register'), $attributes)
            ->assertOk();

        $this
            ->assertDatabaseHas('companies', [
                'name' => $this->company->name,
                'company_email' => $this->user->email,
                'company_phone' => $this->company->phone_number,
                'company_website' => $this->company->website_url,
            ])
            ->assertDatabaseHas('integrations', [
                'name' => $this->company->name,
                'username' => Str::slug($this->company->name, '_'),
                'server' =>  config('fabric.integration_server'),
            ])
            ->assertDatabaseHas('users', [
                'name' => $this->user->name,
                'email' => $this->user->email
            ]);

        $company = Company::with('users')->first();
        $user = $company->users->first();

        $this->assertTrue($user->hasRole('client admin'));
        $this->assertNull($company->trial_ends_at);
        $this->assertTrue($company->integrations()->first()->users->contains($user));
        $this->assertTrue($company->subscriptions()->first()->name === 'Sandbox');
        $this->assertTrue(Hash::check($password, $user->password));
    }

    public function test_users_can_register_with_sso(): void
    {
        $account = new SocialiteUser;
        $account->id = 1;
        $account->email = $this->user->email;
        $account->user['email_verified'] = true;

        $provider = 'google';

        Cache::put('social_token', encrypt(compact('provider', 'account')), now()->addMinutes(20));

        Socialite::shouldReceive('driver->stateless->user')->andReturn($account);

        $attributes = array_merge_recursive($this->attributes, [
            'social_token' => 'social_token'
        ]);

        $this
            ->postJson(route('api.v2.register'), $attributes)
            ->assertOk();

        $this
            ->assertDatabaseHas('companies', [
                'name' => $this->company->name,
                'company_email' => $this->user->email,
                'company_phone' => $this->company->phone_number,
                'company_website' => $this->company->website_url,
            ])
            ->assertDatabaseHas('integrations', [
                'name' => $this->company->name,
                'username' => Str::slug($this->company->name, '_'),
                'server' =>  config('fabric.integration_server'),
            ])
            ->assertDatabaseHas('users', [
                'name' => $this->user->name,
                'email' => $this->user->email
            ]);

        $company = Company::with('users')->first();
        $user = $company->users->first();

        $this->assertCount(1, $user->socialAccounts()->whereProvider('google')->get());
        $this->assertNull($company->trial_ends_at);
        $this->assertTrue($user->hasRole('client admin'));
        $this->assertTrue($company->integrations()->first()->users->contains($user));
        $this->assertTrue($company->subscriptions()->first()->name === 'Sandbox');
    }
}
