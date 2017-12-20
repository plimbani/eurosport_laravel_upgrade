<?php

namespace Laraspace\Console\Commands;

use Illuminate\Console\Command;
use File;
use DB;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;

class insertPositionsForPlacingMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:insertPositionForPlacingMatches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert position in database for placing matches';

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
        $tempFixtures = TempFixture::with('competition.TournamentCompetationTemplates.TournamentTemplate', 'categoryAge')->get();
      
        $allTemplateMatchNumber = [];
        foreach($tempFixtures as $fixture) {

            $fix =  DB::table('temp_fixtures')->select('match_number')->where('id',$fixture->id)->first();
            $category = $fixture->categoryAge->group_name . '-' . $fixture->categoryAge->category_age . '-';
            $allTemplateMatchNumber = str_replace( 'CAT.',$category, $fix->match_number);
            $json =  json_decode($fixture->competition->TournamentCompetationTemplates->TournamentTemplate->json_data, true);
            $fixtureMatchNumber = explode('-',$fix->match_number);
            $fixtureMatchNumber = array_splice($fixtureMatchNumber,2,count($fixtureMatchNumber));
            $fixtureMatchNumber = implode('-',$fixtureMatchNumber);
            $allRounds = $json['tournament_competation_format']['format_name'];
            $allUpdatedRounds = $allRounds;
            $lastRound = $allRounds[count($allRounds) - 1];
            $lastMatchType = $lastRound['match_type'][count($lastRound['match_type']) - 1];

            $matchTypeName = $lastMatchType['name'];
            if(isset($lastMatchType['actual_name'])) {
              $matchTypeName = $lastMatchType['actual_name'];
            }
            $isPlacingMatch = strpos($matchTypeName, 'PM');
            
            if ($isPlacingMatch !== false) {
                $matches = $lastMatchType['groups']['match'];
                $position = 1;
                foreach($matches as $matchKey=>$match) {
                    $matchNumber = str_replace( 'CAT.','', $match['match_number']);     
                    if($fixtureMatchNumber == $matchNumber){
                        if(isset($match['position'])){
                            TempFixture::where('id', $fixture['id'])->update(['position'=> $match['position']]);
                        }
                        // dd($fixtureMatchNumber,$matchNumber,$fixture->id,$match['position']);
                    }

                }
            }
        }
    }
}
