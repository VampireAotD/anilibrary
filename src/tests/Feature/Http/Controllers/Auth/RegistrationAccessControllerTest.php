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

    public function testRegistrationAccessScreenCannotBeRenderedForAuthorizedUser(): void
    {
        $this->actingAs($this->createUser())->get(route('registration_access.request'))->assertRedirect();
    }

    public function testRegistrationAccessScreenCanRendered(): void
    {
        $this->get(route('registration_access.request'))
             ->assertOk()
             ->assertInertia(fn(Assert $page) => $page->component('Auth/RegistrationAccess/Create'));
    }

    public function testCannotAcquireRegistrationAccessIfItAlreadyExistsForRequestedEmail(): void
    {
        $invitation = $this->createInvitation(['email' => $this->faker->unique()->email]);

        $this->post(route('registration_access.acquire'), ['email' => $invitation->email])->assertSessionHasErrors(
            'email'
        );
    }

    public function testCannotAcquireRegistrationAccessIfUserWithRequestedEmailExists(): void
    {
        $user = $this->createUser();

        $this->post(route('registration_access.acquire'), ['email' => $user->email])->assertSessionHasErrors('email');
    }

    public function testCanAcquireRegistrationAccess(): void
    {
        $this->assertDatabaseCount(Invitation::class, 0);

        $this->post(route('registration_access.acquire'), ['email' => $email = $this->faker->unique()->email])
             ->assertRedirectToRoute('registration_access.await');

        $this->assertDatabaseCount(Invitation::class, 1);
        $this->assertDatabaseHas(Invitation::class, ['email' => $email, 'status' => StatusEnum::PENDING]);
    }
}
