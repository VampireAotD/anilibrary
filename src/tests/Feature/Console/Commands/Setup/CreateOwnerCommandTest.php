<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Setup;

use App\Enums\RoleEnum;
use App\Repositories\User\UserRepositoryInterface;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Concerns\Fake\CanCreateFakeUsers;

class CreateOwnerCommandTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;

    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->userRepository = $this->app->make(UserRepositoryInterface::class);
    }

    public function testCommandWillNotWorkInProductionEnvironment(): void
    {
        config(['app.env' => 'production']);

        $this->artisan('setup:create-owner')->assertFailed();
    }

    public function testCommandWillFailIfOwnerAlreadyExists(): void
    {
        $this->createOwner();

        $this->artisan('setup:create-owner')->assertFailed();
    }

    public function testCommandWillUseDefaultEmailToCreateOwnerIfProvidedEmailWasInvalid(): void
    {
        $owner = $this->userRepository->findOwner();
        $this->assertNull($owner);

        $this->artisan('setup:create-owner')
             ->expectsQuestion('Provide valid email address or default will be used', $this->faker->randomAscii)
             ->assertOk();

        $owner = $this->userRepository->findOwner();
        $this->assertNotNull($owner);
        $this->assertTrue($owner->hasRole(RoleEnum::OWNER));
        $this->assertTrue($owner->hasVerifiedEmail());
    }

    public function testCommandWillSuccessfullyCreateOwner(): void
    {
        $this->artisan('setup:create-owner')
             ->expectsQuestion('Provide valid email address or default will be used', $email = $this->faker->email)
             ->assertOk();

        $user = $this->userRepository->findByEmail($email);

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole(RoleEnum::OWNER));
        $this->assertTrue($user->hasVerifiedEmail());
    }
}
