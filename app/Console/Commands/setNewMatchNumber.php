<?php

namespace Laraspace\Console\Commands;

use Illuminate\Console\Command;
use Laraspace\Models\TempFixture;
use Laraspace\Api\Contracts\MatchContract;
use Laraspace\Models\TournamentCompetationTemplates;


class setNewMatchNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:newmatchnumber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting up new match number.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MatchContract $matchObj)
    {
        parent::__construct();
        $this->matchObj = $matchObj;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tournamentCompetationTemplates = TournamentCompetationTemplates::all();

        foreach($tournamentCompetationTemplates as $tournamentCompetationTemplate) {
            $tempFixtures = TempFixture::with('competition', 'categoryAge')
                            ->where('temp_fixtures.age_group_id', $tournamentCompetationTemplate->id)
                            ->get();

            $allTemplateMatchNumber = [];
            foreach($tempFixtures as $fixture) {
                $category = $fixture->categoryAge->group_name . '-' . $fixture->categoryAge->category_age . '-';
                $allTemplateMatchNumber[] = str_replace($category, 'CAT.', $fixture->match_number);
            }

            foreach($tempFixtures as $fixture) {
                $category = $fixture->categoryAge->group_name . '-' . $fixture->categoryAge->category_age . '-';

                $data = [];
                $data['roundName'] = $fixture->competition->competation_round_no;
                $data['allTemplateMatchNumber'] = $allTemplateMatchNumber;

                $match = [];
                $match['match_number'] = str_replace($category, 'CAT.', $fixture->match_number);

                $updatedMatchDetail = $this->matchObj->processMatch($data, $match);
                    
                $fixture->display_match_number = str_replace('CAT.', $fixture->categoryAge->category_age . '.', $updatedMatchDetail['display_match_number']);
                $fixture->display_home_team_placeholder_name = $updatedMatchDetail['display_home_team_placeholder_name'];
                $fixture->display_away_team_placeholder_name = $updatedMatchDetail['display_away_team_placeholder_name'];
                $fixture->save();
            }
        }

        $this->info('All DB templates processed.');
    }
}
