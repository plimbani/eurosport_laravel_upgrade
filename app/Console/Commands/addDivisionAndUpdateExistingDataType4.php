<?php

namespace App\Console\Commands;

use App\Models\Competition;
use App\Models\TempFixture;
use App\Models\TournamentCompetationTemplates;
use App\Models\TournamentTemplates;
use Illuminate\Console\Command;

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
        $templateIds = explode(',', $this->argument('templateIds'));
        $type1Templates = ['107'];

        $tournamentTemplates = TournamentTemplates::whereIn('id', $templateIds)->get()->toArray();

        $notMatchedCompetition = [];
        $noMatchedFixtures = [];
        foreach ($tournamentTemplates as $ttkey => $ttvalue) {
            //get json from template
            $templateJson = json_decode($ttvalue['json_data'], true);

            $isType4Template = false;

            if (in_array($ttvalue['id'], $type1Templates)) {
                $isType4Template = true;
            }

            //get tournament_comp_template from template id
            $allTournamentCompTemplates = TournamentCompetationTemplates::join('tournaments', 'tournament_competation_template.tournament_id', '=', 'tournaments.id')->where('tournament_template_id', $ttvalue['id'])->whereNull('tournaments.deleted_at')
                ->select('tournament_competation_template.*')
                ->get()->toArray();

            //$this->info('Fetching all competation template for id :- '.$ttvalue['id']);
            $count = 1;
            foreach ($allTournamentCompTemplates as $tctkey => $tctvalue) {

                // Get all existing competitions
                $tournament_id = $tctvalue['tournament_id'];
                $tournament_comp_template_id = $tctvalue['id'];
                //$this->info('');
                //$this->info('########################################################################################');
                //$this->info('comp template count is :- '.$count);
                //$this->info('Fetching existing age group and id is  :- '.$tctvalue['id']);

                $existingCompetition = Competition::where('competation_round_no', '!=', 'Round 1')->where('tournament_competation_template_id', $tournament_comp_template_id)->where('tournament_id', $tournament_id)->pluck('id', 'name')->toArray();

                $displayName = $tctvalue['group_name'].'-'.$tctvalue['category_age'];
                $ageCategoryOnly = $tctvalue['category_age'];

                // add division entry for current template
                $divisions = $templateJson['tournament_competation_format']['format_name'];
                $positions = $templateJson['tournament_positions'];
                $order = 1;

                $lastDiv = end($divisions);
                $updatedCompetition = '';
                if ($isType4Template) {
                    $checkTempFixtureCount = TempFixture::where('tournament_id', $tournament_id)->where('age_group_id', $tournament_comp_template_id)->count();

                    foreach ($divisions as $dkey => $roundValue) {
                        $islastDiv = false;

                        if ($roundValue == $lastDiv) {
                            $islastDiv = true;
                        }

                        foreach ($roundValue['match_type'] as $matchTypeKey => $matchTypevalue) {
                            //$this->info('iterating match type');
                            $groupNameTocheck = $displayName.'-'.$matchTypevalue['groups']['group_name'];
                            [$compType, $compMatchMultiple] = explode('-', $matchTypevalue['name']);

                            foreach ($matchTypevalue['groups']['match'] as $mkey => $mvalue) {
                                $fixtureRow = false;
                                $updateMatchData = [];
                                $matchNumberArray = explode('.', $mvalue['match_number']);
                                $matchNumberArray = end($matchNumberArray);
                                if ($isType4Template && $islastDiv && $mkey == 0) {

                                    $db_competition_id = TempFixture::where('tournament_id', $tournament_id)->where('age_group_id', $tournament_comp_template_id)->where('match_number', 'like', '%'.$matchNumberArray.'%')->get(['competition_id'])->toArray();

                                    $db_competition_id = head($db_competition_id);
                                    $updatedCompetition = $db_competition_id['competition_id'];

                                }

                                if ($isType4Template && $islastDiv && $mkey > 1) {
                                    $updatedMatchNumber = str_replace('CAT.', $displayName.'-', $mvalue['match_number']);

                                    $updteDisplayMatchNumber = str_replace('CAT.', $ageCategoryOnly.'.', $mvalue['display_match_number']);

                                    $updateMatchData['match_number'] = $updatedMatchNumber;
                                    $updateMatchData['display_match_number'] = $updteDisplayMatchNumber;

                                    $updateMatchData['competition_id'] = $updatedCompetition;

                                    if ($isType4Template && ! array_key_exists('position', $mvalue)) {

                                        $fixtureId = TempFixture::where('tournament_id', $tournament_id)->where('age_group_id', $tournament_comp_template_id)->where('match_number', 'like', '%'.$matchNumberArray.'%')->get(['id'])->toArray();

                                        if (count($fixtureId) > 0) {
                                            $fixtureId = head($fixtureId);
                                            $fixtureRow = true;
                                            TempFixture::where('id', $fixtureId['id'])->update($updateMatchData);
                                        } else {
                                            // find via reverse fixture

                                            [$category, $group, $matchnumber] = explode('.', $updatedMatchNumber);
                                            [$home, $away] = explode('-', $matchnumber);

                                            $findReverseMatch = $away.'-'.$home;

                                            $updatedMatchNumberReverse = implode('.', [$category, $group, $findReverseMatch]);

                                            $fixtureId = TempFixture::where('tournament_id', $tournament_id)->where('age_group_id', $tournament_comp_template_id)->where('match_number', 'like', '%'.$findReverseMatch.'%')->get(['id'])->toArray();

                                            if (count($fixtureId) > 0) {
                                                $updateMatchData['match_number'] = $updatedMatchNumberReverse;

                                                $fixtureId = head($fixtureId);
                                                $fixtureRow = 'reverseFixture';

                                                TempFixture::where('id', $fixtureId['id'])->update($updateMatchData);

                                            }
                                        }
                                    }

                                    if ($fixtureRow === false || $fixtureRow === 'reverseFixture') {
                                        $noMatchedFixtures[$tournament_id.'.'.$tournament_comp_template_id.'.'.$updatedMatchNumber]['tournament_id'] = $tournament_id;

                                        $noMatchedFixtures[$tournament_id.'.'.$tournament_comp_template_id.'.'.$updatedMatchNumber]['age_category_id'] = $tournament_comp_template_id;

                                        $noMatchedFixtures[$tournament_id.'.'.$tournament_comp_template_id.'.'.$updatedMatchNumber]['match_number'] = $updatedMatchNumber;

                                        if ($fixtureRow == 'reverseFixture') {
                                            $noMatchedFixtures[$tournament_id.'.'.$tournament_comp_template_id.'.'.$updatedMatchNumber]['error'] = 'Reverse fixture found and updated competition.';
                                        } elseif ($checkTempFixtureCount == 0) {
                                            $noMatchedFixtures[$tournament_id.'.'.$tournament_comp_template_id.'.'.$updatedMatchNumber]['error'] = 'No fixtures found.';
                                        } else {
                                            $noMatchedFixtures[$tournament_id.'.'.$tournament_comp_template_id.'.'.$updatedMatchNumber]['error'] = 'Need to check manually.';
                                        }

                                    }

                                    //$this->info('iterating temp fixture matches and updated with new competition id , match_number = '.$mvalue['match_number'].' and tournament_id = '.$tournament_id.' and age_group_id = '.$tournament_comp_template_id.'.');

                                }
                            }
                        }
                        $order++;
                    }
                }

                $count++;
            }
            //$this->info("Script executed.");
        }

        echo '<pre> no match fixtures';
        print_r($noMatchedFixtures);
    }
}
