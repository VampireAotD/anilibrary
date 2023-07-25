<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use App\Services\SignedUrlService;
use Carbon\Carbon;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private SignedUrlService $signedUrlService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->signedUrlService = $this->app->make(SignedUrlService::class);
    }

    public function testRegisterScreenCannotBeRenderedIfThereIsNoSignature(): void
    {
        $this->get(route('register'))->assertForbidden();
    }

    public function testRegistrationScreenCannotBeRenderedIfSignatureIsInvalid(): void
    {
        $this->get(
            route('register', [
                'expires'   => now()->unix(),
                'signature' => $this->faker->name,
            ])
        )->assertForbidden();
    }

    public function testRegistrationScreenCannotBeRenderedIfUrlIsExpired(): void
    {
        Redis::shouldReceive('setex')->andReturnTrue();

        $url = $this->signedUrlService->createRegistrationLink($this->faker->email);

        Carbon::setTestNow(now()->addMinutes(config('auth.registration_link_timeout') + 1));

        $this->get($url)->assertForbidden();
    }

    public function testUserCannotRegisterWithUnknownEmail(): void
    {
        Redis::shouldReceive('exists')->andReturnFalse();

        $this->post(route('register'), ['email' => $this->faker->unique()->email])->assertForbidden();
    }

    public function testUserCanRegister(): void
    {
        Redis::shouldReceive('exists')->andReturnTrue();
        Redis::shouldReceive('del')->andReturnTrue();

        $response = $this->post('/register', [
            'name'                  => $this->faker->name,
            'email'                 => $this->faker->unique()->email,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
