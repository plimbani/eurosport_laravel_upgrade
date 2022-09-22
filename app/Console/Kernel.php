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
        Commands\generatePositionsForExistingData::class,
        Commands\sendActivityNotification::class,
        Commands\removeDanglingImages::class,
        Commands\RemoveExpirePreviewDomain::class,
        Commands\addDivisionAndUpdateExistingData::class,
        Commands\addDivisionAndUpdateExistingDataType1::class,
        Commands\addDivisionAndUpdateExistingDataType2::class,
        Commands\addDivisionAndUpdateExistingDataType3::class,
        Commands\addDivisionAndUpdateExistingDataType4::class,
        Commands\generateFavicon::class,
        Commands\downloadUsers::class,
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
        $schedule->command('activity:notification')->everyThirtyMinutes();
        $schedule->command('setup:removeDanglingImages')->dailyAt('03:00');
        $schedule->command('setup:removePreviewUrl')->everyFiveMinutes();
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
