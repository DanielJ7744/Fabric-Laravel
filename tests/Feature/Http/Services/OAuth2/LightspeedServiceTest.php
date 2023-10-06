<?php

namespace Tests\Feature\Http\Services\OAuth2;

use App\Facades\SystemOAuth2;
use App\Http\Services\OAuth2\LightspeedService;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\System;
use App\Models\Fabric\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\LaravelTestCase;

/**
 * @group connectors
 */
class LightspeedServiceTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->system = factory(System::class)->create(['factory_name' => 'Lightspeed']);
        $this->user = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make([
            'name' => 'Test integration UK',
            'username' => 'table'
        ]));
    }

    public function test_request_access_token_method(): void
    {
        $request = new Request(['code' => '123']);
        $response = new Response(200, [], json_encode(['access_token' => 1, 'refresh_token' => 2]));
        $mock = $this->partialMock(LightspeedService::class, fn ($mock) => $mock
            ->shouldReceive('requestAccessToken')
            ->with($request)
            ->andReturn($response));

        SystemOAuth2::partialMock()
            ->shouldReceive('driver')
            ->with('Lightspeed')
            ->andReturn($mock);

        $driver = SystemOAuth2::driver($this->system->factory_name);
        $this->assertEquals($response, $driver->requestAccessToken($request));
    }

    public function test_validate_method(): void
    {
        $responseContent = ['access_token' => 1, 'refresh_token' => 2];
        $response = new Response(200, [], json_encode($responseContent));
        $mock = $this->partialMock(LightspeedService::class, fn ($mock) => $mock
            ->shouldReceive('validate')
            ->with($response)
            ->andReturn($responseContent));

        SystemOAuth2::partialMock()
            ->shouldReceive('driver')
            ->with('Lightspeed')
            ->andReturn($mock);

        $driver = SystemOAuth2::driver($this->system->factory_name);
        $this->assertEquals($responseContent, $driver->validate($response));
    }

    public function test_redirect_method(): void
    {
        $request = new Request();
        $response = new \Illuminate\Http\Response(['redirect_url' => 'test']);
        $mock = $this->partialMock(LightspeedService::class, fn ($mock) => $mock
            ->shouldReceive('redirect')
            ->with($request)
            ->andReturn($response));

        SystemOAuth2::partialMock()
            ->shouldReceive('driver')
            ->with('Lightspeed')
            ->andReturn($mock);

        $driver = SystemOAuth2::driver($this->system->factory_name);
        $responseContent = json_decode($driver->redirect($request)->getContent(), true);
        $this->assertEquals($responseContent, ['redirect_url' => 'test']);
    }
}
