<?php

namespace Laraspace\Console\Commands;

use Illuminate\Console\Command;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\AgeCategoryDivision;
use Laraspace\Models\Competition;

class addDivisionAndUpdateExistingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:divisionUpdate';

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
        $tournamentTemplates = TournamentTemplates::where('no_of_divisions','>',0)->whereNotNull('no_of_divisions')->get()->toArray();

        $notMatchedCompetition = [];
        foreach ($tournamentTemplates as $ttkey => $ttvalue) {
            //get json from template
            $templateJson = json_decode($ttvalue['json_data'], true);

            //get tournament_comp_template from template id
            $allTournamentCompTemplates = TournamentCompetationTemplates::where('tournament_template_id',$ttvalue['id'])->limit(1)->get()->toArray();

            foreach ($allTournamentCompTemplates as $tctkey => $tctvalue) {

                // Get all existing competitions
                $tournament_id = $tctvalue['tournament_id'];
                $tournament_comp_template_id = $tctvalue['id'];

                $existingCompetition = Competition::where('competation_round_no','!=','Round 1')->where('tournament_competation_template_id',$tournament_comp_template_id)->where('tournament_id',$tournament_id)->pluck('id','name')->toArray();

                $displayName = $tctvalue['group_name'].'-'.$tctvalue['category_age'];


                // add division entry for current template
                $divisions = $templateJson['tournament_competation_format']['divisions'];
                $order = 1;
                foreach ($divisions as $dkey => $dvalue) {
                    $divData = [];
                    $divData['name'] = "Division ".$order;
                    $divData['order'] = $order;
                    $divData['tournament_id'] = $tournament_id;
                    $divData['tournament_competition_template_id'] = $tournament_comp_template_id;

                    $result = AgeCategoryDivision::create($divData);
                    $newDivisionId = $result->id;
                    //$newDivisionId = 5;


                    foreach ($dvalue['format_name'] as $roundkey => $roundValue) {
                        $roundName = $roundValue['name'];
                        foreach ($roundValue['match_type'] as $matchTypeKey => $matchTypevalue) {
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

                                    $notMatchedCompetition[$competitionUpdateId] = $competitionData;
                                    Competition::where('id',$competitionUpdateId)->update($competitionData);

                                    $needToRemovekey = array_search($competitionUpdateId,$existingCompetition);
                                    unset($existingCompetition[$needToRemovekey]);
                                }
                                else
                                {
                                    $compResult = Competition::create($competitionData);
                                    $competitionUpdateId = $compResult->id;
                                }
                            }

                            foreach ($matchTypevalue['groups']['match'] as $mkey => $mvalue) {
                                $updateMatchData = [];
                                $updateMatchData['competition_id'] = $competitionUpdateId;

                                $updatedMatchNumber = str_replace('CAT.', $displayName.'-', $mvalue['match_number']);
                                TempFixture::where('tournament_id',$tournament_id)->where('age_group_id',$tournament_comp_template_id)->where('match_number',$updatedMatchNumber)->update($updateMatchData);


                            }
                        }
                    }
                    $order++;
                }
            }

            echo "<pre>";print_r("done for existing Data");exit();
        }
    }
}
