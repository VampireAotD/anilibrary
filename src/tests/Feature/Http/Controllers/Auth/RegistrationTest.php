<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Auth;

use App\Notifications\Auth\VerifyEmailNotification;
use App\Services\Url\SignedUrlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private SignedUrlService $signedUrlService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signedUrlService = $this->app->make(SignedUrlService::class);
    }

    public function testRegisterScreenCannotBeRenderedIfThereIsNoSignature(): void
    {
        $this->get(route('register'))->assertForbidden();
    }

    public function testRegistrationScreenCannotBeRenderedIfSignatureIsInvalid(): void
    {
        $this->get(route('register', [
            'expires'   => now()->unix(),
            'signature' => $this->faker->name,
        ]))->assertForbidden();
    }

    public function testRegistrationScreenCannotBeRenderedIfUrlIsExpired(): void
    {
        Cache::shouldReceive('add')->andReturnTrue();

        $url = $this->signedUrlService->createRegistrationLink($this->faker->email);

        $this->travel(config('auth.registration_link_timeout') + 1)->minutes();

        $this->get($url)->assertForbidden();
    }

    public function testUserCannotRegisterWithUnknownEmail(): void
    {
        Cache::shouldReceive('has')->andReturnFalse();

        $this->post(route('register'), ['email' => $this->faker->unique()->email])->assertForbidden();
    }

    public function testUserCannotRegisterTwoTimesWithSameLink(): void
    {
        Notification::fake();
        Cache::shouldReceive('add')->once()->andReturnTrue();
        Cache::shouldReceive('has')->twice()->andReturnTrue();

        $email = $this->faker->unique()->safeEmail();
        $url   = $this->signedUrlService->createRegistrationLink($email);

        $this->get($url)->assertOk();

        Cache::shouldReceive('delete')->once()->andReturnTrue();

        $response = $this->post(route('register'), [
            'name'                  => $this->faker->name,
            'email'                 => $email,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        Notification::assertCount(1);
        Notification::assertSentTo(auth()->user(), VerifyEmailNotification::class);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        Auth::logout();
        $this->assertFalse(Auth::check());

        Cache::shouldReceive('has')->once()->andReturnFalse();
        $this->get($url)->assertForbidden();
    }

    public function testUserCanRegister(): void
    {
        Notification::fake();
        Cache::shouldReceive('has')->andReturnTrue();
        Cache::shouldReceive('delete')->andReturnTrue();

        $response = $this->post(route('register'), [
            'name'                  => $this->faker->name,
            'email'                 => $this->faker->unique()->email,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        Notification::assertCount(1);
        Notification::assertSentTo(auth()->user(), VerifyEmailNotification::class);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
