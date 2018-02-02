<?php

namespace Laraspace\Console\Commands;

use Carbon\Carbon;
use Laraspace\Models\User;
use Illuminate\Console\Command;
use Laraspace\Models\Tournament;

class automaticallyPermissionRemoval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:automaticallypermissionremoval';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For Tournament administrators automatically remove their access once the tournament is finished.
';

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
        $yesterdayDate = Carbon::yesterday()->toDateString();
        $allTournaments = Tournament::whereDate('end_date','<=',$yesterdayDate)->pluck('id')->toArray();
        $users = User::whereHas('roles', function($query)
                {
                    $query->where('slug', 'tournament.administrator');
                })->get();
        foreach ($users as $user) {
            $userTournamnets = $user->tournaments()->detach($allTournaments);
        }
        $this->info('Script executed.');        
    }
}
