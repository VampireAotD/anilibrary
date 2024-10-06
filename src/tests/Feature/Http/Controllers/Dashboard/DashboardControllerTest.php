<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Enums\Anime\StatusEnum;
use App\Models\Anime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeUsers;
    use CanCreateFakeAnime;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCannotAccessDashboardUnauthenticated(): void
    {
        $this->get(route('dashboard'))->assertRedirectToRoute('login');
    }

    public function testCanViewDashboard(): void
    {
        $owner      = $this->createOwner();
        $collection = $this->createAnimeCollection(10);
        $completed  = $collection->filter(fn(Anime $anime) => $anime->status === StatusEnum::READY);

        $this->actingAs($owner)->get(route('dashboard'))->assertInertia(
            fn(Assert $page) => $page->component('Dashboard/Index')
                                     ->has('latestAnime', $collection->count())
                                     ->has('completedAnime', $completed->count())
                                     ->has('mostPopularAnime', $collection->count())
        );
    }
}
