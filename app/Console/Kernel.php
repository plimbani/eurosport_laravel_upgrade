<?php

namespace Laraspace\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\setHomeAndAwayTeamPlaceholder::class,
        Commands\setNewMatchNumber::class,
        Commands\generateAdditionalBreaks::class,
        Commands\generateTournamentSlugForExistingData::class,
        Commands\insertPitchSize::class,
        Commands\insertPositionsForPlacingMatches::class,
        Commands\automaticallyPermissionRemoval::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('setup:automaticallypermissionremoval')->dailyAt('00:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
