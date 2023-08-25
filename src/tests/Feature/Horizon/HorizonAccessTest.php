<?php

declare(strict_types=1);

namespace Tests\Feature\Horizon;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\Fake\CanCreateFakeUsers;

class HorizonAccessTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function testHorizonDashboardCannotBeAccessedInProductionEnvironment(): void
    {
        config(['app.env' => 'production']);

        $user = $this->createAdmin();

        $this->actingAs($user)->get('/horizon')->assertForbidden();
    }

    public function testHorizonDashboardCanBeAccessedByOwnerInProductionEnvironment(): void
    {
        config(['app.env' => 'production']);

        $user = $this->createOwner();

        $this->actingAs($user)->get('/horizon')->assertOk();
    }
}
