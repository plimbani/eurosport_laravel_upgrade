<?php

namespace Laraspace\Console\Commands;

use DB;
use Illuminate\Console\Command;

class setHomeAndAwayTeamPlaceholder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:teamplaceholder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting up home and away placeholder team name.';

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
        $tempFixtures = DB::table('temp_fixtures')->get();        
        
        foreach($tempFixtures as $fixture) {            
            $matchNumber = $fixture->match_number;
            $separatedArray = explode('.', $matchNumber);            

            if(!isset($separatedArray[2])) {                
                $this->info("{$fixture->id}!");
                continue;
            }

            $finalData = explode('-', $separatedArray[2]);

            if(count($finalData) != 2) {
                $this->info("{$fixture->id}!");
                continue;
            }

            DB::table('temp_fixtures')
                ->where('id', $fixture->id)
                ->update([
                    'home_team_placeholder_name' => $finalData[0],
                    'away_team_placeholder_name' => $finalData[1]
                ]);
        }

        $this->info('Script executed.');
    }
}
