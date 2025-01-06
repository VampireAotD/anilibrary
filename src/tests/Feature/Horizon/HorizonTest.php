<?php

declare(strict_types=1);

namespace Tests\Feature\Horizon;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class HorizonTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;

    public function testHorizonDashboardCanBeAccessedOnlyByOwner(): void
    {
        $this->actingAs($this->createUser())->get(route('horizon.index'))->assertForbidden();
        $this->actingAs($this->createAdmin())->get(route('horizon.index'))->assertForbidden();

        $this->actingAs($this->createOwner())->get(route('horizon.index'))->assertOk();
    }

    public function testHorizonDashboardCanBeAccessedOnlyByOwnerInProductionEnvironment(): void
    {
        config(['app.env' => 'production']);

        $this->actingAs($this->createUser())->get(route('horizon.index'))->assertForbidden();
        $this->actingAs($this->createAdmin())->get(route('horizon.index'))->assertForbidden();

        $this->actingAs($this->createOwner())->get(route('horizon.index'))->assertOk();
    }
}
