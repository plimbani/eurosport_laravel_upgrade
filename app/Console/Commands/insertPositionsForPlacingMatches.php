<?php

namespace Laraspace\Console\Commands;

use Illuminate\Console\Command;
use File;
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
        $files = File::allFiles('public/templates');
        // dd($files);
        // echo "<pre>"; print_r('test123'); echo "</pre>"; exit;
        $tournamentCompetationTemplates = TournamentCompetationTemplates::get()->take(5);
         $tempFixtures = TempFixture::with('competition.TournamentCompetationTemplates.TournamentTemplate', 'categoryAge')
                        ->get()->take(10);
                        // dd($tempFixtures);
            
            $allTemplateMatchNumber = [];
            foreach($tempFixtures as $fixture) {
                $category = $fixture->categoryAge->group_name . '-' . $fixture->categoryAge->category_age . '-';
                $allTemplateMatchNumber = str_replace( 'CAT.',$category, $fixture->match_number);
                $json =  json_decode($fixture->competition->TournamentCompetationTemplates->TournamentTemplate->json_data, true);
                
                $allRounds = $json['tournament_competation_format']['format_name'];
                
                $allUpdatedRounds = $allRounds;
                $lastRound = $allRounds[count($allRounds) - 1];
                $lastMatchType = $lastRound['match_type'][count($lastRound['match_type']) - 1];

                $matchTypeName = $lastMatchType['name'];
                if(isset($lastMatchType['actual_name'])) {
                  $matchTypeName = $lastMatchType['actual_name'];
                }
                $isPlacingMatch = strpos($matchTypeName, 'PM');
                // if ($isPlacingMatch !== false) {
                //     dd($file);
                // // }
                // dd($allUpdatedRounds[count($allRounds) - 1]['match_type'][count($lastRound['match_type']) - 1]['groups']['match'][$matchKey]);
                if ($isPlacingMatch !== false) {
                  // echo $file. '<br/>';
                  $matches = $lastMatchType['groups']['match'];
                  $position = 1;
                  foreach($matches as $matchKey=>$match) {
                    $updatedMatchDetail = $match;
                    TempFixture::where('id', $fixture['id'])->update(['position'=> $match['position']]);
                    
                  }
                }
            // dd('test');
            }

            // foreach($tempFixtures as $fixture) {
            //     $category = $fixture->categoryAge->group_name . '-' . $fixture->categoryAge->category_age . '-';

            //     dd($fixture);

                // $data = [];
                // $data['roundName'] = $fixture->competition->competation_round_no;
                // $data['allTemplateMatchNumber'] = $allTemplateMatchNumber;

                // $match = [];
                // $match['match_number'] = str_replace($category, 'CAT.', $fixture->match_number);

                // $updatedMatchDetail = $this->matchObj->processMatch($data, $match);
                    
                // $fixture->display_match_number = str_replace('CAT.', $fixture->categoryAge->category_age . '.', $updatedMatchDetail['display_match_number']);
                // $fixture->display_home_team_placeholder_name = $updatedMatchDetail['display_home_team_placeholder_name'];
                // $fixture->display_away_team_placeholder_name = $updatedMatchDetail['display_away_team_placeholder_name'];
                // $fixture->save();
            // }
        // }

    }
}
