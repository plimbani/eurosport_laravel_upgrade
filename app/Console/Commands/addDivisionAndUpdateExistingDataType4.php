<?php

namespace Laraspace\Console\Commands;

use Illuminate\Console\Command;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\AgeCategoryDivision;
use Laraspace\Models\Competition;
use Laraspace\Models\Position;

class addDivisionAndUpdateExistingDataType4 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:divisionUpdateType4{templateIds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add division and update existing competition and fixtures';

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
        $templateIds = explode(',',$this->argument('templateIds'));
        $type1Templates = array('T.19.4');

        $tournamentTemplates = TournamentTemplates::where('no_of_divisions','>',0)->whereNotNull('no_of_divisions')->whereIn('id',$templateIds)->limit(1)->get()->toArray();

        $notMatchedCompetition = [];
        foreach ($tournamentTemplates as $ttkey => $ttvalue) {
            //get json from template
            $templateJson = json_decode($ttvalue['json_data'], true);

            $isType4Template = false;        

            if ( in_array($ttvalue['name'], $type1Templates))
            {
                $isType4Template = true;
            }

            //get tournament_comp_template from template id
            $allTournamentCompTemplates = TournamentCompetationTemplates::where('tournament_template_id',$ttvalue['id'])->where('id','654')->limit(1)->get()->toArray();


            $this->info('Fetching all competation template for id :- '.$ttvalue['id']);
            $count = 1;
            foreach ($allTournamentCompTemplates as $tctkey => $tctvalue) {

                // Get all existing competitions
                $tournament_id = $tctvalue['tournament_id'];
                $tournament_comp_template_id = $tctvalue['id'];
                $this->info('');
                $this->info('########################################################################################');
                $this->info('comp template count is :- '.$count);
                $this->info('Fetching existing age group and id is  :- '.$tctvalue['id']);

                $existingCompetition = Competition::where('competation_round_no','!=','Round 1')->where('tournament_competation_template_id',$tournament_comp_template_id)->where('tournament_id',$tournament_id)->pluck('id','name')->toArray(); 


                $displayName = $tctvalue['group_name'].'-'.$tctvalue['category_age'];
                $ageCategoryOnly = $tctvalue['category_age'];


                // add division entry for current template
                $divisions = $templateJson['tournament_competation_format']['format_name'];
                $positions = $templateJson['tournament_positions'];
                $order = 1;

                $lastDiv = end($divisions);

                foreach ($divisions as $dkey => $roundValue) {
                    $islastDiv = false;

                    if ( $roundValue == $lastDiv)
                    {
                        $islastDiv = true;
                    }

                    foreach ($roundValue['match_type'] as $matchTypeKey => $matchTypevalue)         {
                        $this->info('iterating match type');
                        $groupNameTocheck = $displayName.'-'.$matchTypevalue['groups']['group_name'];
                        list($compType,$compMatchMultiple) = explode('-',$matchTypevalue['name']);
                        

                        foreach ($matchTypevalue['groups']['match'] as $mkey => $mvalue) {
                            $updateMatchData = [];

                            if ( $isType4Template && $islastDiv && $mkey > 1 )
                            {

                                $updatedMatchNumber = str_replace('CAT.', $displayName.'-', $mvalue['match_number']);


                                $updteDisplayMatchNumber = str_replace('CAT.', $ageCategoryOnly.'.', $mvalue['display_match_number']);

                                $updateMatchData['match_number'] = $updatedMatchNumber;
                                $updateMatchData['display_match_number'] = $updteDisplayMatchNumber;

                                if ( $isType4Template && !array_key_exists('position', $mvalue))
                                {
                                    $matchNumberArray = explode('.',$mvalue['match_number']);
                                    $matchNumberArray = end($matchNumberArray);


                                    TempFixture::where('tournament_id',$tournament_id)->where('age_group_id',$tournament_comp_template_id)->where('match_number','like','%'.$matchNumberArray.'%')->update($updateMatchData);
                                }

                                $this->info('iterating temp fixture matches and updated with new competition id , match_number = '.$mvalue['match_number'].' and tournament_id = '.$tournament_id.' and age_group_id = '.$tournament_comp_template_id.'.');
                                
                            }
                        }
                    }
                    $order++;
                }

                $count++;
            }
            echo "<pre>no match existing competition name issue";print_r($notMatchedCompetition);
            $this->info("Script executed.");
        }

        echo "<pre>noMatchedPosition";print_r($noMatchedPosition);
    }
}
