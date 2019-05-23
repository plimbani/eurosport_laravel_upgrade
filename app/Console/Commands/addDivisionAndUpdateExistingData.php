<?php

namespace Laraspace\Console\Commands;

use Illuminate\Console\Command;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\AgeCategoryDivision;


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

        foreach ($tournamentTemplates as $ttkey => $ttvalue) {
            //get json from template
            $templateJson = json_decode($ttvalue['json_data'], true);

            //get tournament_comp_template from template id
            $allTournamentCompTemplates = TournamentCompetationTemplates::where('tournament_template_id',$ttvalue['id'])->limit(1)->get()->toArray();

            foreach ($allTournamentCompTemplates as $tctkey => $tctvalue) {
                $tournament_id = $tctvalue['tournament_id'];
                $tournament_comp_template_id = $tctvalue['id'];
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

                    //$result = AgeCategoryDivision::create($divData);
                    //$newDivisionId = $result->id;
                    $newDivisionId = 5;
                    echo "<pre>";print_r($result->id);exit();
                    $order++;
                }

            }
        }
    }
}
