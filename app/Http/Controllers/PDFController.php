<?php

namespace Laraspace\Http\Controllers;

use Laraspace\Models\Team;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\Tournament;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Api\Repositories\TemplateRepository;
use DB;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function getFooter(Request $request)
    {	
      $query = $request->all();
      return view('summary.footer', compact('query'));	
    }

    public function getMatchSchedulePdfFooter(Request $request)
    {
        $query = $request->all();
        return view('age_category.match_schedule_pdf_footer', compact('query'));
    }

    public function matchgraphic(Request $request)
    {
        $request = $request->all();

        $templateId = (isset($request['templateId']) && $request['templateId']) ? $request['templateId'] : null;
      $ageCategoryId = $request['ageCategoryId'];
      $date = new \DateTime(date('H:i d M Y'));

      $tournamentTemplateData = [];
      $tournamentTemplateData['json_data'] = '';        
      $tempFixtures = DB::table('temp_fixtures')->where('age_group_id', $ageCategoryId)
          ->leftjoin('venues', 'temp_fixtures.venue_id', '=', 'venues.id')
          ->leftjoin('pitches', 'temp_fixtures.pitch_id', '=', 'pitches.id')
          ->select(['temp_fixtures.match_number', 'temp_fixtures.display_match_number', 'temp_fixtures.home_team', 'temp_fixtures.home_team_name', 'temp_fixtures.away_team', 'temp_fixtures.away_team_name', 'venues.name as venue_name', 'pitches.pitch_number as pitch_name', 'pitches.size as pitch_size', 'temp_fixtures.is_scheduled as is_scheduled', 'temp_fixtures.match_datetime as match_datetime'])
          ->where('temp_fixtures.deleted_at', NULL)
          ->get()->keyBy('match_number')->toArray();
      $tempFixtures = array_map(function($object){
          return (array) $object;
      }, $tempFixtures);
      $assignedTeams = Team::where('age_group_id', $ageCategoryId)->whereNotNull('competation_id')->get()->toArray();
      $roundMatches = [];
      $divisionMatches = [];
      $allMatches = [];
      $tournamentCompetitionTemplate = TournamentCompetationTemplates::find($ageCategoryId);
      $tournamentData = Tournament::where('id', '=', $tournamentCompetitionTemplate->tournament_id)->select(DB::raw('CONCAT("'.getenv('S3_URL').'/assets/img/tournament_logo/'.'", logo) AS tournamentLogo'))->first();

      if($templateId != NULL) {
          $tournamentTemplate                  = TournamentTemplates::find($templateId);
          $tournamentTemplateData['json_data'] = $tournamentTemplate->json_data;
          $tournamentTemplateData['image']     = $tournamentTemplate->image;
      } else {
          $tournamentTemplateData['json_data'] = $tournamentCompetitionTemplate->template_json_data;
      }
      $jsonData = json_decode($tournamentTemplateData['json_data'], true);
      $roundMatches = TemplateRepository::getMatches($jsonData['tournament_competation_format']['format_name']);
      if(isset($jsonData['tournament_competation_format']['divisions'])) {
          foreach($jsonData['tournament_competation_format']['divisions'] as $divisionIndex => $division) {
              $matches = TemplateRepository::getMatches($division['format_name']);
              $divisionMatches = array_merge($divisionMatches, $matches);
          }
      }
      $allMatches = array_merge($roundMatches, $divisionMatches);

      return view('age_category.match_schedule_graphic', [
              'fixtures' => $tempFixtures,
              'templateData' => $jsonData,
              'assignedTeams' => $assignedTeams,
              'categoryAge' => $tournamentCompetitionTemplate->category_age,
              'groupName' => $tournamentCompetitionTemplate->group_name,
              'allMatches' => $allMatches,
              'tournamentData' => $tournamentData,
              'date' => $date->format('H:i d M Y'),
          ]);
    }
}
