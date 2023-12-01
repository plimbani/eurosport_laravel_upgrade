<?php

namespace App\Console\Commands;

use App\Api\Repositories\AgeGroupRepository;
use App\Models\Position;
use App\Models\TournamentCompetationTemplates;
use App\Models\TournamentTemplates;
use Illuminate\Console\Command;

class generatePositionsForExistingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:generatePositionsForExistingData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert positions for the existing age categories.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->ageGroupServiceObj = new \App\Api\Services\AgeGroupService(new AgeGroupRepository());
        $this->matchServiceObj = new \App\Api\Services\MatchService();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tournamentTemplates = TournamentTemplates::get()->keyBy('id')->toArray();
        $tournamentCompetationTemplates = TournamentCompetationTemplates::all();
        foreach ($tournamentCompetationTemplates as $tournamentCompetationTemplate) {
            $this->ageGroupServiceObj->insertPositions($tournamentCompetationTemplate->id, $tournamentTemplates[$tournamentCompetationTemplate->tournament_template_id]);
            $matchPositions = Position::where('age_category_id', $tournamentCompetationTemplate->id)->where('dependent_type', 'match')->get();
            $this->matchServiceObj->updatePlacingMatchPositions($tournamentCompetationTemplate, $matchPositions);

            $rankingPositions = Position::where('age_category_id', $tournamentCompetationTemplate->id)->where('dependent_type', 'ranking')->get();
            $this->matchServiceObj->updateGroupRankingPositions($tournamentCompetationTemplate, $rankingPositions);
        }
        $this->info('Script executed.');
    }
}
