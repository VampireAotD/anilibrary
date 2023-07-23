<?php

declare(strict_types=1);

namespace App\Console\Commands\Lists\Anime;

use App\DTO\Service\Anime\CreateAnimeDTO;
use App\Models\AnimeSynonym;
use App\Models\AnimeUrl;
use App\Models\Genre;
use App\Models\VoiceActing;
use App\Services\AnimeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class ParseCommand
 * @package App\Console\Commands\Lists\Anime
 */
class ParseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anime-list:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse all anime data from list';

    /**
     * Create a new command instance.
     */
    public function __construct(private readonly AnimeService $animeService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $pathToFile = config('lists.anime.file');

        if (!Storage::disk('lists')->exists($pathToFile)) {
            $this->warn('Anime list not found');
            return Command::FAILURE;
        }

        $animeList = json_decode(Storage::disk('lists')->get($pathToFile), true);
        $bar       = $this->output->createProgressBar(count($animeList));

        foreach ($animeList as $parsed) {
            DB::transaction(function () use ($parsed) {
                $anime = $this->animeService->create(
                    new CreateAnimeDTO($parsed['title'], $parsed['status'], $parsed['rating'], $parsed['episodes'])
                );

                $synonyms = collect($parsed['synonyms'])->mapInto(AnimeSynonym::class);
                $anime->synonyms()->saveMany($synonyms);

                $urls = collect($parsed['urls'])->mapInto(AnimeUrl::class);
                $anime->urls()->saveMany($urls);

                $anime->image()->create($parsed['image']);

                Genre::upsert($parsed['genres'], 'name');
                $anime->genres()->sync(collect($parsed['genres'])->pluck('id')->toArray());

                VoiceActing::upsert($parsed['voice_acting'], 'name');
                $anime->voiceActing()->sync(collect($parsed['voice_acting'])->pluck('id')->toArray());
            });

            $bar->advance(1);
        }

        $bar->finish();
        $this->newLine()->info('Parsed anime list');

        return Command::SUCCESS;
    }
}
