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
    protected $signature = 'setup:unfavouriteUserTournamentRemove';

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
        $allTournaments = Tournament::whereDate('end_date','<', $createdDateBeforeOneMonth)->pluck('id')->toArray();

        $allExpireUserFavourites = UserFavourites::whereIn('tournament_id', $allTournaments)->where('is_default',1)->whereNull('deleted_at')->get();

        if ( $allExpireUserFavourites->count() > 0 )
        {
            $allExpireUserFavourites = $allExpireUserFavourites->toArray();
        }

        $tounamentWillDelete = [];
        foreach ($allExpireUserFavourites as $fkey => $expireTournament) {
            $tounamentWillDelete[] = $expireTournament['tournament_id'];
            $findUserHasAnother = UserFavourites::where('user_id', $expireTournament['user_id'])->where('tournament_id','!=',$expireTournament['tournament_id'])->whereNull('deleted_at')->get();

            if ( $findUserHasAnother->count() > 0 )
            {
                $firstTournament = $findUserHasAnother->first();
                UserFavourites::where('id', $firstTournament['id'])->update(['is_default' => 1]);
            }
            UserFavourites::where('id', $expireTournament['id'])->update(['is_default' => 0]);
        }
        $tounamentWillDelete = array_unique($tounamentWillDelete);
        UserFavourites::whereIn('tournament_id', $tounamentWillDelete)->delete();

        $this->info('Script executed.');
    }
}
