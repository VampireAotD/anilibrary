<?php

declare(strict_types=1);

namespace App\Console\Commands\AnimeList;

use App\Mail\AnimeListMail;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

/**
 * Class Generate
 * @package App\Console\Commands\AnimeList
 */
class Generate extends Command
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
                'url',
                'status',
                'rating',
                'episodes',
            ],
            [
                'image:id,model_id,path,alias',
                'tags:id,name',
                'genres:id,name',
                'voiceActing:id,name',
            ]
        );

        File::put(config('filesystems.animeListPath'), $animeList->toJson(JSON_PRETTY_PRINT));

        Mail::to(config('admin.email'))->queue(new AnimeListMail());

        return Command::SUCCESS;
    }
}
