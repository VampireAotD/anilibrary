<?php

declare(strict_types=1);

namespace App\Console\Commands\Lists\Anime;

use App\Filters\ColumnFilter;
use App\Filters\RelationFilter;
use App\Mail\AnimeListMail;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\AnimeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

final class GenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anime-list:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate anime list';

    /**
     * Execute the console command.
     */
    public function handle(AnimeService $animeService, UserRepositoryInterface $userRepository): int
    {
        if (!$owner = $userRepository->findOwner()) {
            $this->error('Owner not found');
            return self::FAILURE;
        }

        $animeList = $animeService->all([
            new ColumnFilter(['id', 'title', 'status', 'rating', 'episodes']),
            new RelationFilter([
                'urls:anime_id,url',
                'synonyms:anime_id,synonym',
                'image:id,model_id,path,alias',
                'genres:id,name',
                'voiceActing:id,name',
            ]),
        ]);

        Storage::disk('lists')->put(config('lists.anime.file'), $animeList->toJson(JSON_PRETTY_PRINT));

        Mail::to($owner->email)->queue(new AnimeListMail());

        $this->info('Anime list successfully generated');

        return self::SUCCESS;
    }
}
