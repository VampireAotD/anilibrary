<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Elasticsearch\UpsertAnimeJob;
use App\Models\Anime;

/**
 * Class AnimeObserver
 * @package App\Observers
 */
class AnimeObserver
{
    /**
     * Handle the Anime "created" event.
     */
    public function created(Anime $anime): void
    {
        UpsertAnimeJob::dispatch($anime);
    }

    /**
     * Handle the Anime "updated" event.
     */
    public function updated(Anime $anime): void
    {
        UpsertAnimeJob::dispatch($anime);
    }

    /**
     * Handle the Anime "deleted" event.
     */
    public function deleted(Anime $anime): void
    {
        //
    }

    /**
     * Handle the Anime "restored" event.
     */
    public function restored(Anime $anime): void
    {
        //
    }

    /**
     * Handle the Anime "force deleted" event.
     */
    public function forceDeleted(Anime $anime): void
    {
        //
    }
}
