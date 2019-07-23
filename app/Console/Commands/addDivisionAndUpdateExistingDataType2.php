<?php

namespace Laraspace\Console\Commands;

use Illuminate\Console\Command;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\AgeCategoryDivision;
use Laraspace\Models\Competition;
use Laraspace\Models\Position;

class addDivisionAndUpdateExistingDataType2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:divisionUpdateType2{templateIds}';

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
        $type1DiffFormateTemplates = array('New TT58','New TT85','New TT135','T.28.6 (v1)','T.13.5');

        $tournamentTemplates = TournamentTemplates::where('no_of_divisions','>',0)->whereNotNull('no_of_divisions')->whereIn('id',$templateIds)->limit(1)->get()->toArray();

        $notMatchedCompetition = [];
        $noMatchedPosition = [];
        foreach ($tournamentTemplates as $ttkey => $ttvalue) {
            //get json from template
            $templateJson = json_decode($ttvalue['json_data'], true);

            $isType1DiffFormateTemplates = false;      

            if ( in_array($ttvalue['name'], $type1DiffFormateTemplates))
            {
                $isType1DiffFormateTemplates = true;
            }

            //get tournament_comp_template from template id
            $allTournamentCompTemplates = TournamentCompetationTemplates::where('tournament_template_id',$ttvalue['id'])->limit(1)->get()->toArray();

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
                $divisions = $templateJson['tournament_competation_format']['divisions'];
                $positions = $templateJson['tournament_positions'];
                $order = 1;

                $lastDiv = end($divisions);

                foreach ($divisions as $dkey => $dvalue) {
                    $islastDiv = false;

                    if ( $dvalue == $lastDiv || ( $dkey > 0 && ( $ttvalue['name'] == 'T.28.6 (v1)' || $ttvalue['name'] == 'New TT58' ))) 
                    {
                        $islastDiv = true;
                    }

                    $this->info('Create a new entry in division table for tournament_id = '.$tournament_id.' and tournament_competition_template_id = '.$tournament_comp_template_id);


                    $divData = [];
                    $divData['name'] = "Division ".$order;
                    $divData['order'] = $order;
                    $divData['tournament_id'] = $tournament_id;
                    $divData['tournament_competition_template_id'] = $tournament_comp_template_id;

                    $result = AgeCategoryDivision::create($divData);
                    $newDivisionId = $result->id;
                    //$newDivisionId = 5;

                    $lastround = end($dvalue['format_name']);
                    foreach ($dvalue['format_name'] as $roundkey => $roundValue) {
                        $islastRound = false;

                        if ( $roundValue == $lastround)
                        {
                            $islastRound = true;
                        }

                        $roundName = $roundValue['name'];
                        $this->info('_________________________________________________________________________________________');
                        $this->info('looping on new round.');

                        foreach ($roundValue['match_type'] as $matchTypeKey => $matchTypevalue) {
                            $this->info('iterating match type');
                            $groupNameTocheck = $displayName.'-'.$matchTypevalue['groups']['group_name'];
                            list($compType,$compMatchMultiple) = explode('-',$matchTypevalue['name']);

                            $competitionData = [];
                            $competitionData['tournament_competation_template_id'] = $tournament_comp_template_id;
                            $competitionData['tournament_id'] = $tournament_id;
                            $competitionData['age_category_division_id'] = $newDivisionId;
                            $competitionData['display_name'] = $tournament_id;
                            $competitionData['team_size'] = $matchTypevalue['group_count'];
                            $competitionData['competation_type'] = ($compType == 'RR') ? 'Round Robin' : 'Elimination';
                            $competitionData['actual_competition_type'] = ($compType == 'RR') ? 'Round Robin' : 'Elimination';
                            $competitionData['competation_round_no'] = $roundName;

                            

                            if (array_key_exists($groupNameTocheck, $existingCompetition) )
                            {

                                $competitionData['name'] = $groupNameTocheck;
                                $competitionData['display_name'] = $groupNameTocheck;
                                $competitionData['actual_name'] = $groupNameTocheck;

                                $competitionUpdateId = $existingCompetition[$groupNameTocheck];

                                Competition::where('id',$competitionUpdateId)->update($competitionData);
                                $this->info('Update Existing competition with new value , and comp id is :- '.$competitionUpdateId);
                                unset($existingCompetition[$groupNameTocheck]);
                            }
                            else
                            {

                                $competitionData['name'] = $groupNameTocheck;
                                $competitionData['display_name'] = $groupNameTocheck;
                                $competitionData['actual_name'] = $groupNameTocheck;
                                if ( sizeof($existingCompetition) > 0 )
                                {
                                    // if not matched then overwrite existing one
                                    $competitionUpdateId = head($existingCompetition);

                                    $notMatchedCompetition[$competitionUpdateId] = "id = ".$competitionUpdateId." and tournament_id = ".$tournament_id;

                                    Competition::where('id',$competitionUpdateId)->update($competitionData);
                                    $this->info('Not match with DB competiton , Update Existing competition with new value. , and comp id is :- '.$competitionUpdateId);
                                    $needToRemovekey = array_search($competitionUpdateId,$existingCompetition);
                                    unset($existingCompetition[$needToRemovekey]);
                                }
                                else
                                {

                                    $compResult = Competition::create($competitionData);
                                    $competitionUpdateId = $compResult->id;
                                    $this->info('Create new competition and id is :- '.$competitionUpdateId);
                                }
                            }

                            foreach ($matchTypevalue['groups']['match'] as $mkey => $mvalue) {
                                $updateMatchData = [];
                                $positionMatchData = [];
                                $updateMatchData['competition_id'] = $competitionUpdateId;

                                if ( $isType1DiffFormateTemplates && $islastRound && $islastDiv )
                                {
                                    $positionMatchData['match_number'] = $mvalue['match_number'];

                                    $updatedMatchNumber = str_replace('CAT.', $displayName.'-', $mvalue['match_number']);


                                    $updteDisplayMatchNumber = str_replace('CAT.', $ageCategoryOnly.'.', $mvalue['display_match_number']);

                                    $updateMatchData['match_number'] = $updatedMatchNumber;
                                    $updateMatchData['display_match_number'] = $updteDisplayMatchNumber;

                                    
                                    TempFixture::where('tournament_id',$tournament_id)->where('age_group_id',$tournament_comp_template_id)->where('position',$mvalue['position'])->update($updateMatchData);

                                    $tempFixturePositions = explode('-',$mvalue['position']);

                                    Position::where('age_category_id',$tournament_comp_template_id)->where('dependent_type','match')->whereIn('position',$tempFixturePositions)->update($positionMatchData);
                                }


                                $this->info('iterating temp fixture matches and updated with new competition id , match_number = '.$mvalue['match_number'].' and tournament_id = '.$tournament_id.' and age_group_id = '.$tournament_comp_template_id.' and new updated competition is '.$competitionUpdateId.'.');
                                    
                            }
                        }
                    }
                    $order++;
                }


                // update match positions
                foreach ($positions as $pkey => $pvalue) {
                    $updatePositionRow = [];

                    $dbRowFound = false;
                    $updatePositionRow['position'] = $pvalue['position'];
                    if ( $pvalue['dependent_type'] == 'match')
                    {
                        $positionMatchNumberArray = explode('.',$pvalue['match_number']);
                        $positionMatchNumberArray = end($positionMatchNumberArray);
                        $resultType = $pvalue['result_type'];

                        $posId = Position::where('age_category_id',$tournament_comp_template_id)->where('result_type',$resultType)->where('dependent_type','match')->where('match_number','like','%'.$positionMatchNumberArray.'%')->get(['id'])->toArray();

                        if ( sizeof($posId) > 0 )
                        {
                            $posId = head($posId);
                            $dbRowFound = true;
                            Position::where('id',$posId['id'])->update($updatePositionRow);
                        }
                    }

                    if ( $pvalue['dependent_type'] == 'ranking')
                    {
                        $ranking = $pvalue['ranking'];

                        $posId = Position::where('age_category_id',$tournament_comp_template_id)->where('dependent_type','ranking')->where('ranking',$ranking)->get(['id'])->toArray();

                        if ( sizeof($posId) > 0 )
                        {
                            $posId = head($posId);
                            $dbRowFound = true;
                            Position::where('id',$posId['id'])->update($updatePositionRow);
                        }
                    }

                    if ( $dbRowFound == false )
                    {
                        $noMatchedPosition[$tournament_comp_template_id.$pvalue['match_number']]['match_number'] = $pvalue['match_number'];

                        $noMatchedPosition[$tournament_comp_template_id.$pvalue['match_number']]['age_category_id'] = $tournament_comp_template_id;
                        $noMatchedPosition[$tournament_comp_template_id.$pvalue['match_number']]['position'] = $pvalue['position'];
                        $noMatchedPosition[$tournament_comp_template_id.$pvalue['match_number']]['dependent_type'] = $pvalue['dependent_type'];

                        $noMatchedPosition[$tournament_comp_template_id.$pvalue['match_number']]['result_type'] = $pvalue['result_type'];

                    }
                }

                $count++;
            }
            echo "<pre>no match existing competition name issue";print_r($notMatchedCompetition);
            $this->info("Script executed.");
        }
        echo "<pre>noMatchedPosition";print_r($noMatchedPosition);
    }
}
