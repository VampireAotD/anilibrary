<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Setup;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateOwnerCommandTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

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

        $this->artisan('setup:create-owner')
             ->assertFailed();
    }

    public function testCommandWillFailIfOwnerAlreadyExists(): void
    {
        User::factory()->create()->assignRole(RoleEnum::OWNER->value);

        $this->artisan('setup:create-owner')
             ->assertFailed();
    }

    public function testCommandWillFailIfUserWillExceedMaximumAttemptsToProvideValidEmailAddress(): void
    {
        $user = User::factory()->create();

        $this->artisan('setup:create-owner')
             ->expectsQuestion('Provide valid email address', null)
             ->expectsQuestion('Provide valid email address', $this->faker->word)
             ->expectsQuestion('Provide valid email address', $user->email)
             ->expectsOutput('Exceeded maximum tries to provide valid email address')
             ->assertFailed();
    }

    public function testCommandWillSuccessfullyCreateOwner(): void
    {
        $this->artisan('setup:create-owner')
             ->expectsQuestion('Provide valid email address', $email = $this->faker->email)
             ->assertOk();

        $user = $this->userRepository->findByEmail($email);

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole(RoleEnum::OWNER->value));
        $this->assertTrue($user->hasVerifiedEmail());
    }
}
