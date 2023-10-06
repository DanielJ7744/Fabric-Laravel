<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Helpers\ElasticsearchHelper;
use Carbon\Carbon;
use App\Models\Fabric\Company;
use App\Models\Fabric\Integration;
use App\Models\Fabric\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class MappingControllerTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = factory(Company::class)->create();
        $this->withoutPermissions = $this->company->users()->save(factory(User::class)->make());
        $this->withPermissions = $this->company->users()->save(factory(User::class)->states('client user')->make());
        $this->integration = $this->company->integrations()->save(factory(Integration::class)->make());
    }

    public function testWithoutPermissionsCannotRead(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->getJson('api/v2/mappings/1')
            ->assertForbidden();
    }

    public function testWithReadMappingsPermissionCanRead(): void
    {
        $createdAt = Carbon::now()->toRfc3339String();
        $mockGetResponseData = [
            '_id' => '1',
            '_source' => [
                'username' => $this->integration->username,
                'search_field' => '1',
                'mapping_name' => '1',
                'overrides' => '1',
                'created_at' => $createdAt,
                'content' => '{"testA": "testB"}',
            ],
        ];
        $this->partialMock(ElasticsearchHelper::class, function ($mock) use ($mockGetResponseData) {
            $mock->shouldReceive('get')->once()->andReturn($mockGetResponseData);
        });

        $this
            ->passportAs($this->withPermissions)
            ->getJson('api/v2/mappings/1')
            ->assertOk();
    }

    public function testWithoutPermissionsCannotCreate(): void
    {
        $attributes = [
            'integration_id' => $this->integration->id,
            'mapping_name' => 'test_mapping_name',
            'content' => '{"testA": "testB"}'
        ];

        $this
            ->passportAs($this->withoutPermissions)
            ->postJson('api/v2/mappings', $attributes)
            ->assertForbidden();
    }

    public function testWithCreateMappingsPermissionCanCreate(): void
    {
        $createdAt = Carbon::now()->toRfc3339String();
        $mockGetResponseData = [
            '_id' => '1',
            '_source' => [
                'username' => $this->integration->username,
                'search_field' => '1',
                'mapping_name' => '1',
                'overrides' => '1',
                'created_at' => $createdAt,
                'content' => '{"testA": "testB"}',
            ],
        ];
        $this->partialMock(ElasticsearchHelper::class, function ($mock) use ($mockGetResponseData) {
            $mock->shouldReceive('get')->once()->andReturn($mockGetResponseData);
            $mock->shouldReceive('post')->once();
            $mock->shouldReceive('put')->once();
        });

        $attributes = [
            'integration_id' => $this->integration->id,
            'mapping_name' => 'test_mapping_name',
            'content' => '{"testA": "testB"}'
        ];

        $this
            ->passportAs($this->withPermissions)
            ->postJson('api/v2/mappings', $attributes)
            ->assertOk();
    }

    public function testWithoutPermissionsCannotDelete(): void
    {
        $this
            ->passportAs($this->withoutPermissions)
            ->delete('api/v2/mappings/1')
            ->assertForbidden();
    }

    public function testWithDeleteMappingsPermissionCanDelete(): void
    {
        $this->partialMock(ElasticsearchHelper::class, function ($mock) {
            $mock->shouldReceive('delete')->once();
        });

        $this
            ->passportAs($this->withPermissions)
            ->delete('api/v2/mappings/1')
            ->assertOk()
            ->assertJsonPath('message', 'Mapping deleted successfully.');
    }
}
