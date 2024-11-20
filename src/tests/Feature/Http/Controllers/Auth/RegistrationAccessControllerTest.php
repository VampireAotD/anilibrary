<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Enums\Invitation\StatusEnum;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Concerns\Fake\CanCreateFakeInvitations;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class RegistrationAccessControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;
    use CanCreateFakeInvitations;

    public function testRegistrationAccessScreenCanRendered(): void
    {
        $this->get(route('register-access.create'))
             ->assertOk()
             ->assertInertia(fn(Assert $page) => $page->component('Auth/RegistrationAccess/Create'));
    }

    public function testRegisteredUserCannotAcquireRegistrationAccess(): void
    {
        $this->actingAs($this->createUser())->get(route('register-access.create'))->assertRedirect();
    }

    public function testCannotAcquireRegistrationAccessIfItAlreadyExistsForRequestedEmail(): void
    {
        $invitation = $this->createInvitation(['email' => $this->faker->unique()->email]);

        $this->post(route('register-access.store'), ['email' => $invitation->email])->assertSessionHasErrors('email');
    }

    public function testCannotAcquireRegistrationAccessIfUserWithRequestedEmailExists(): void
    {
        $user = $this->createUser();

        $this->post(route('register-access.store'), ['email' => $user->email])->assertSessionHasErrors('email');
    }

    public function testCanAcquireRegistrationAccess(): void
    {
        $this->assertDatabaseCount(Invitation::class, 0);

        $this->post(route('register-access.store'), ['email' => $email = $this->faker->unique()->email])
             ->assertRedirectToRoute('register-access.show');

        $this->assertDatabaseCount(Invitation::class, 1);
        $this->assertDatabaseHas(Invitation::class, ['email' => $email, 'status' => StatusEnum::PENDING]);
    }
}
