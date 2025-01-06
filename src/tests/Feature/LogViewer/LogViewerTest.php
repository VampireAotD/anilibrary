<?php

declare(strict_types=1);

namespace Tests\Feature\LogViewer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class LogViewerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;

    public function testLogViewerDashboardCanBeAccessedOnlyByOwner(): void
    {
        $this->actingAs($this->createUser())->get(route('log-viewer.index'))->assertForbidden();
        $this->actingAs($this->createAdmin())->get(route('log-viewer.index'))->assertForbidden();

        $this->actingAs($this->createOwner())->get(route('log-viewer.index'))->assertOk();
    }

    public function testLogViewerDashboardCanBeAccessedOnlyByOwnerInProductionEnvironment(): void
    {
        config(['app.env' => 'production']);

        $this->actingAs($this->createUser())->get(route('log-viewer.index'))->assertForbidden();
        $this->actingAs($this->createAdmin())->get(route('log-viewer.index'))->assertForbidden();

        $this->actingAs($this->createOwner())->get(route('log-viewer.index'))->assertOk();
    }
}
