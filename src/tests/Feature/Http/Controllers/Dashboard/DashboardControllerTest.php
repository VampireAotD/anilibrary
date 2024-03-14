<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Dashboard;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeUsers;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeUsers;
    use CanCreateFakeAnime;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function testCannotAccessDashboardUnauthenticated(): void
    {
        $this->get(route('dashboard'))->assertRedirectToRoute('login');
    }

    public function testCanViewDashboard(): void
    {
        $collection = $this->createAnimeCollection(10);
        $owner      = $this->createOwner();

        $this->createAdmin();

        $this->actingAs($owner)->get(route('dashboard'))->assertInertia(
            fn(Assert $page) => $page->component('Dashboard/Index')
                                     ->has('animeCount')
                                     ->where('animeCount', $collection->count())
                                     ->has('usersCount')
                                     ->where('usersCount', 2)
                                     ->has('animePerMonth')
                                     ->has('animePerDomain')
                                     ->has('latestAnime')
                                     ->where('latestAnime.0.id', $collection->first()->id)
        );
    }
}
