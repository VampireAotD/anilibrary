<?php

declare(strict_types=1);

use App\Console\Commands\Anime\UpdateUnreleasedAnimeCommand;
use App\Console\Commands\Elasticsearch\Index\Anime\SyncAnimeDataCommand;
use App\Console\Commands\Lists\Anime\GenerateCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::call(GenerateCommand::class)->dailyAt('12:00');
Schedule::call(SyncAnimeDataCommand::class)->lastDayOfMonth();
Schedule::call(UpdateUnreleasedAnimeCommand::class)->mondays();
