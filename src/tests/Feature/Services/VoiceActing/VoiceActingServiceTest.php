<?php

declare(strict_types=1);

namespace Tests\Feature\Services\VoiceActing;

use App\Enums\VoiceActingEnum;
use App\Models\VoiceActing;
use App\Services\VoiceActing\VoiceActingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoiceActingServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private int                $voiceActingCount;
    private VoiceActingService $voiceActingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->voiceActingService = $this->app->make(VoiceActingService::class);
        $this->voiceActingCount   = count(VoiceActingEnum::cases());
    }

    public function testWillNotCreateOrUpdateNewVoiceActingIfNoVoiceActingAreProvided(): void
    {
        $this->assertDatabaseCount(VoiceActing::class, $this->voiceActingCount);

        $this->voiceActingService->sync([]);
        $this->assertDatabaseCount(VoiceActing::class, $this->voiceActingCount);
    }

    public function testCanCreateNewVoiceActing(): void
    {
        $this->assertDatabaseCount(VoiceActing::class, $this->voiceActingCount);

        $voiceActing = VoiceActing::factory(10)->make()->pluck('name')->map(
            fn(string $genre) => ['name' => $genre]
        );

        $this->voiceActingService->sync($voiceActing->toArray());
        $this->assertDatabaseCount(VoiceActing::class, $this->voiceActingCount + count($voiceActing));
    }

    public function testCanUpsertVoiceActing(): void
    {
        $voiceActing = VoiceActing::factory(10)->create();
        $this->assertDatabaseCount(VoiceActing::class, $this->voiceActingCount + 10);

        $newVoiceActing = $voiceActing->pluck('name')->merge($this->faker->words(5))->map(
            fn(string $genre) => ['name' => $genre]
        );

        $this->voiceActingService->sync($newVoiceActing->toArray());
        $this->assertDatabaseHas(VoiceActing::class, ['name' => $newVoiceActing->toArray()]);
    }
}
