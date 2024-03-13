<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\Anime\UpdateUnreleasedAnimeCommand;
use App\Console\Commands\Elasticsearch\Index\Anime\SyncAnimeDataCommand;
use App\Console\Commands\Lists\Anime\GenerateCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(GenerateCommand::class)->dailyAt('12:00');
        $schedule->command(SyncAnimeDataCommand::class)->lastDayOfMonth();
        $schedule->command(UpdateUnreleasedAnimeCommand::class)->mondays();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
