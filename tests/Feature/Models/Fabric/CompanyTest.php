<?php

namespace Tests\Feature\Models\Fabric;

use App\Models\Fabric\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\LaravelTestCase;

class CompanyTest extends LaravelTestCase
{
    use RefreshDatabase;

    public function test_trial_is_active_when_in_the_future(): void
    {
        $company = factory(Company::class)->make(['trial_ends_at' => now()->addSeconds(10)]);

        $this->assertFalse($company->trialExpired());
    }

    public function test_trial_is_expired_when_in_the_past(): void
    {
        $company = factory(Company::class)->make(['trial_ends_at' => now()->subSecond()]);

        $this->assertTrue($company->trialExpired());
    }

    public function test_trial_is_bypassed_when_null(): void
    {
        $company = factory(Company::class)->make(['trial_ends_at' => null]);

        $this->assertFalse($company->trialExpired());
    }
}
