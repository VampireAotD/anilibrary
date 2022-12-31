<?php

declare(strict_types=1);

namespace Tests\Feature\UseCase;

use App\DTO\UseCase\CallbackQuery\AddedAnimeDTO;
use App\DTO\UseCase\CallbackQuery\PaginationDTO;
use App\Services\Telegram\Base62Service;
use App\UseCase\CallbackQueryUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;

class CallbackQueryUseCaseTest extends TestCase
{
    use RefreshDatabase,
        WithFaker,
        CanCreateFakeData;

    private Base62Service        $base62Service;
    private CallbackQueryUseCase $callbackQueryUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->base62Service        = $this->app->make(Base62Service::class);
        $this->callbackQueryUseCase = $this->app->make(CallbackQueryUseCase::class);
    }

    /**
     * @return void
     */
    public function testCannotReturnCaptionOfUnknownAnime(): void
    {
        $encoded = $this->base62Service->encode(Str::orderedUuid()->toString());
        $caption = $this->callbackQueryUseCase->addedAnimeCaption(
            new AddedAnimeDTO($encoded, $this->faker->randomNumber())
        );

        $this->assertEmpty($caption);
    }

    /**
     * @return void
     */
    public function testCanCreateCaptionForAddedAnime(): void
    {
        $anime   = $this->createRandomAnimeWithRelations()->first();
        $encoded = $this->base62Service->encode($anime->id);
        $caption = $this->callbackQueryUseCase->addedAnimeCaption(
            new AddedAnimeDTO($encoded, $this->faker->randomNumber())
        );

        $this->assertNotNull($caption);
        $this->assertEquals($anime->id, $this->base62Service->decode($encoded));
        $this->assertEquals($anime->caption, $caption['caption']);
        $this->assertEquals($anime->image->path, $caption['photo']);
        $this->assertNotEmpty($caption['reply_markup']['inline_keyboard']);
        $this->assertCount($anime->urls->count(), reset($caption['reply_markup']['inline_keyboard']));
    }

    /**
     * @return void
     */
    public function testCanCreatePaginationCaption(): void
    {
        $animeList  = $this->createRandomAnimeWithRelations(2);
        $pagination = $this->callbackQueryUseCase->paginate(new PaginationDTO($this->faker->randomNumber()));
        $anime      = $animeList->first();

        $this->assertNotNull($pagination);
        $this->assertEquals($anime->caption, $pagination['caption']);
        $this->assertEquals($anime->image->path, $pagination['photo']);
        $this->assertNotEmpty($pagination['reply_markup']['inline_keyboard'][1]);
        $this->assertCount(1, $pagination['reply_markup']['inline_keyboard'][1]);
        $this->assertEquals('>', $pagination['reply_markup']['inline_keyboard'][1][0]['text']);

        $pagination = $this->callbackQueryUseCase->paginate(new PaginationDTO($this->faker->randomNumber(), 2));

        $anime = $animeList->last();

        $this->assertNotNull($pagination);
        $this->assertEquals($anime->caption, $pagination['caption']);
        $this->assertEquals($anime->image->path, $pagination['photo']);
        $this->assertNotEmpty($pagination['reply_markup']['inline_keyboard'][1]);
        $this->assertCount(1, $pagination['reply_markup']['inline_keyboard'][1]);
        $this->assertEquals('<', $pagination['reply_markup']['inline_keyboard'][1][0]['text']);
    }
}
