<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Setup;

use App\Console\Commands\Setup\CreateOwnerCommand;
use App\Enums\RoleEnum;
use App\Services\User\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class CreateOwnerCommandTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;

    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = $this->app->make(UserService::class);
    }

    public function testCommandWillNotWorkInProductionEnvironment(): void
    {
        config(['app.env' => 'production']);

        $this->artisan(CreateOwnerCommand::class)->assertFailed();
    }

    public function testCommandWillFailIfOwnerAlreadyExists(): void
    {
        $this->createOwner();

        $this->artisan(CreateOwnerCommand::class)->assertFailed();
    }

    public function testCommandWillUseDefaultEmailToCreateOwnerIfProvidedEmailWasInvalid(): void
    {
        $owner = $this->userService->getOwner();
        $this->assertNull($owner);

        $this->artisan(CreateOwnerCommand::class)
             ->expectsQuestion('Provide valid email address or default will be used', $this->faker->randomAscii)
             ->assertOk();

        $owner = $this->userService->getOwner();
        $this->assertNotNull($owner);
        $this->assertTrue($owner->hasRole(RoleEnum::OWNER));
        $this->assertTrue($owner->hasVerifiedEmail());
    }

    public function testCommandWillSuccessfullyCreateOwner(): void
    {
        $this->artisan(CreateOwnerCommand::class)
             ->expectsQuestion('Provide valid email address or default will be used', $email = $this->faker->email)
             ->assertOk();

        $user = $this->userService->findByEmail($email);

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole(RoleEnum::OWNER));
        $this->assertTrue($user->hasVerifiedEmail());
    }
}
