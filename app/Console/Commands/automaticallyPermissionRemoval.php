<?php

namespace Laraspace\Console\Commands;

use Carbon\Carbon;
use Laraspace\Models\User;
use Laraspace\Models\Role;
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
        $mobileUserRoleId = Role::where('slug', 'mobile.user')->first()->id;
        $yesterdayDate = Carbon::yesterday()->toDateString();
        $allTournaments = Tournament::whereDate('end_date','<=',$yesterdayDate)->pluck('id')->toArray();
        $users = User::whereHas('roles', function($query)
                {
                    $query->where('slug', 'tournament.administrator');
                })->get();
        foreach ($users as $user) {
            $userTournaments = $user->tournaments();
            $userTournamentIds = $userTournaments->pluck('id');
            $intersectTournaments = $userTournamentIds->intersect($allTournaments)->values();
            if(count($intersectTournaments) > 0) {
                if($userTournaments->count() == 1) {
                    $user->is_desktop_user = 0;
                    $user->save();
                    $user->detachAllRoles();
                    $user->attachRole($mobileUserRoleId);
                }
                $userTournaments->detach($intersectTournaments);
            }
        }
        $this->info('Script executed.');
    }
}
