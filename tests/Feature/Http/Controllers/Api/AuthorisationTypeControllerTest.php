<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Fabric\AuthorisationType;
use App\Models\Fabric\Company;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class AuthorisationTypeControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->authorisationType = factory(AuthorisationType::class)->create();
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
    }

    public function test_users_can_retrieve_authorisation_types(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.authorisation-types.index'))
            ->assertOk();
    }

    public function test_users_can_retrieve_an_authorisation_type(): void
    {
        $this
            ->passportAs($this->withPermissions)
            ->getJson(route('api.v2.authorisation-types.show', $this->authorisationType))
            ->assertOk()
            ->assertJsonPath('data.id', $this->authorisationType->id);
    }
}
