<?php

declare(strict_types=1);

use App\Console\Commands\Anime\UpdateUnreleasedAnimeCommand;
use App\Console\Commands\Elasticsearch\Index\Anime\ImportAnimeDataCommand;
use App\Console\Commands\Lists\Anime\GenerateCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(GenerateCommand::class)->dailyAt('12:00');
Schedule::command(ImportAnimeDataCommand::class)->lastDayOfMonth();
Schedule::command(UpdateUnreleasedAnimeCommand::class)->mondays();
