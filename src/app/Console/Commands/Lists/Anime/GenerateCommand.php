<?php

declare(strict_types=1);

namespace App\Console\Commands\Lists\Anime;

use App\Mail\AnimeListMail;
use App\Repositories\Anime\AnimeRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * Class GenerateCommand
 * @package App\Console\Commands\Lists\Anime
 */
class GenerateCommand extends Command
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
     * Create a new command instance.
     */
    public function __construct(private readonly AnimeRepositoryInterface $animeRepository)
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
        $animeList = $this->animeRepository->getAll(
            [
                'id',
                'title',
                'status',
                'rating',
                'episodes',
            ],
            [
                'urls:anime_id,url',
                'synonyms:anime_id,synonym',
                'image:id,model_id,path,alias',
                'genres:id,name',
                'voiceActing:id,name',
            ]
        );

        Storage::disk('lists')->put(config('lists.anime.file'), $animeList->toJson(JSON_PRETTY_PRINT));

        Mail::to(config('mail.owner.address'))->queue(new AnimeListMail());

        $this->info('Anime list successfully generated');

        return Command::SUCCESS;
    }
}
