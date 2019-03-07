<?php

namespace Laraspace\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Laraspace\Models\Tournament;
use Laraspace\Models\UserFavourites;

class unfavouriteUserTournamentRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:unfavouriteUserTournamentRemove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unfavourite user tournament remove.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $createdDateBeforeOneMonth = Carbon::now()->subDays(28)->format('Y-m-d');
        $allTournaments = Tournament::whereDate('end_date','>=', $createdDateBeforeOneMonth)->pluck('id')->toArray();
        foreach ($allTournaments as $tournament) {
            $tournamentId = UserFavourites::where('tournament_id', $tournament)->delete();
        }
        $this->info('Script executed.');
    }
}
