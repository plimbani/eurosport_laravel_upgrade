<?php

namespace Laraspace\Api\Services;

use DB;
use PDF;
use Carbon\Carbon;
use Laraspace\Models\Club;
use Laraspace\Models\Team;
use Laraspace\Models\Tournament;
use Laraspace\Models\TempFixture;
use Laraspace\Models\Competition;
use Laraspace\Traits\TournamentAccess;
use Laraspace\Api\Contracts\TeamContract;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Api\Repositories\TeamRepository;
use Laraspace\Models\TournamentCompetationTemplates;


class TeamService implements TeamContract
{
    use TournamentAccess;
  
    public function __construct(TeamRepository $teamRepoObj)
    {
        $this->teamRepoObj = $teamRepoObj;
        $this->matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
        $this->tournamentRepoObj = new \Laraspace\Api\Repositories\TournamentRepository();
        $this->tournamentLogo =  getenv('S3_URL').'/assets/img/tournament_logo/';
    }

    /*
     * Get All Teams
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getTeams($teamData)
    {
        $data = $teamData->toArray()['teamData'];
        $resultEnteredHomeTeams = TempFixture::where('temp_fixtures.tournament_id', $data['tournamentId'])
        ->whereNotNull('hometeam_score')
        ->get()->pluck('home_team')->toArray();
        $resultEnteredAwayTeams = TempFixture::where('temp_fixtures.tournament_id', $data['tournamentId'])
        ->whereNotNull('awayteam_score')
        ->get()->pluck('away_team')->toArray();
        $resultEnteredTeams = array_unique(array_merge($resultEnteredHomeTeams, $resultEnteredAwayTeams));
        
        // Here we send Status Code and Messages
        $data = $this->teamRepoObj->getAllFromFilter($data);

        if ($data) {
            return ['status_code' => '200', 'data' => $data, 'resultEnteredTeams' => $resultEnteredTeams];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    public function getClubs($tournamentData)
    {

        // Here we send Status Code and Messages
        $data = $this->teamRepoObj->getClubData($tournamentData['tournamentData']);
        // print_r($data);exit;
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    public function getClubTeams($tournamentData)
    {
        $data = $this->teamRepoObj->getTeamData($tournamentData['tournamentData']);
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }
          return ['status_code' => '505', 'message' => 'Error in Data'];
    }


    public function getAllTournamentTeams($data)
    {
        $token = \JWTAuth::getToken();
        $tournamentId = $data['tournamentData']['tournamentId'];
        $data = $this->teamRepoObj->getAllTournamentTeams($data['tournamentData']['tournamentId']);
        $competitionFixtures = null;

        if(!$token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {
          $competitionFixtures = Competition::withCount('scheduledFixtures')
                                              ->where('tournament_id', $tournamentId)
                                              ->pluck('scheduled_fixtures_count', 'id')
                                              ->toArray();
        }

        if ($data) {
          return ['status_code' => '200', 'data' => $data, 'competitionFixtures' => $competitionFixtures];
        }

        return ['status_code' => '505', 'message' => 'Error in Data'];
    }
    public function getAllFromCompetitionId($data)
    {
        $data = $this->teamRepoObj->getAllFromCompetitionId($data['tournamentData']['competitionId']);
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data']; 
    }
    

    /**
     * create New Team.
     *
     * @param  [type]
     * @param mixed $data
     *
     * @return [type]
     */
    public function getCountryIdFromName($countryName) {

        $cid = \DB::table('countries')->where('name', $countryName)->select('id')->first();
        if($cid){
            return $cid->id;
        }else{
            return "error";
        }


        // return 1;
    }
    public function create($data, $tournamentId)
    {
        if($data['country']!=''){
            $data['country_id'] = $this->getCountryIdFromName($data['country']) != 'error' ? $this->getCountryIdFromName($data['country']) : '1';
        } else {
            $data['country_id'] = '1';
        }
        $data['age_group_id'] = 0;

        $ageCategory = trim($data['agecategory']);
        $categoryName = trim($data['categoryname']);
        if($ageCategory!= '' && $categoryName!=''){
          $competitionData = TournamentCompetationTemplates::where('tournament_id', $tournamentId)->where('category_age', $ageCategory)->where('group_name', $categoryName)->first();
          if($competitionData){ 
            $data['age_group_id'] = $competitionData['id'];
          }
        }

        $teamData = $this->teamRepoObj->getTeambyTeamId($data['teamid'], $tournamentId);
        if($data['club']!='')
        {
            // Here we first find the club name in Database
            $clubData1 = Club::where('name',$data['club'])->get();
            // Here we check if No data then create
            if(count($clubData1) == 0) {
                // Its New values
                $club_array = array('user_id'=>'1','name'=>$data['club']);
                $clubData = Club::create($club_array);
                $update = [
                  'tournament_id' => $tournamentId,
                  'club_id' => $clubData->id,
                ];
                // print_r($update);exit;
                $data['club_id'] = $clubData->id;
                $clubData->tournament()->attach($update);
            }
            else {
                // here check if record is exist
               $club_id = $clubData1[0]->id;
               $tournament_id = $tournamentId;
              $clubData2 = Club::whereHas('tournament', function($q) use ($club_id,$tournament_id) {
                  $q->where('tournament_id',$tournament_id);
                  $q->where('club_id',$club_id);
              })->exists();

                if(!$clubData2) {
                  // we have to insert the value in tournament id
                   $updateEd = [
                  'tournament_id' =>  $tournamentId,
                  'club_id' => $club_id,
                ];
                  \DB::table('tournament_club')->insert($updateEd);
                  //$clubData1->tournament()->attach($updateEd);
                }

                $data['club_id'] = $club_id;
            }
        }

        if($data['age_group_id'] != 0){
            if(isset($teamData['id']) ){
                $editData =  [
                    'id' => $teamData['id'],
                    'name' => $data['team'],
                    'place' => $data['place'],
                    'country_id' => $data['country_id'],
                    'club_id' => $data['club_id'],
                    'age_group_id' => $data['age_group_id'],
                    'comments' => $data['teamcomment'],
                    'shirt_color' => $data['shirtcolor'],
                    'shorts_color' => $data['shortscolor'],
                ];

                $data = $this->teamRepoObj->edit($editData, $teamData['id']);
            } else {
                $data = $this->teamRepoObj->create($data, $tournamentId);
            }
        }

        if ($data) {
          return ['status_code' => '200', 'message' => 'Data Sucessfully Inserted'];
        }
    }

    /**
     * Edit Team.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function edit($data)
    {
        $data = $data->all();
        $data = $this->teamRepoObj->edit($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
        }
    }

    public function assignTeams($data)
    {
      // $this->UpdateMatches($data);
      $teamsData = $this->teamRepoObj->getAllUpdatedTeam($data);
      $teamsList = $this->teamRepoObj->getAllGroupTeam($teamsData);
      $tournamentId = $data['data']['tournament_id'];
      $ageGroupId  = $data['data']['age_group'];
       
      $teamData = $data['data']['teamdata'];

      // for group assignment validation
      $tempFixturesCount = TempFixture::where('tournament_id', $data['data']['tournament_id'])
                                  ->where('age_group_id', $data['data']['age_group'])
                                  ->where(function($query){
                                    $query->orWhereNotNull('hometeam_score')
                                          ->orWhereNotNull('awayteam_score');
                                  })
                                  ->get()
                                  ->count();

      if($tempFixturesCount > 0) { 
        
        $tournamentCompetationTemplatesTotalTeamsCount = TournamentCompetationTemplates::where('id', $data['data']['age_group'])->first();

        $finalTeamdata = [];
        foreach ($teamData as $key => $team) {
          if($team['value'] != '') {
            $finalTeamdata[] = $team;
          }
        } 

        if(count($finalTeamdata) != $tournamentCompetationTemplatesTotalTeamsCount->total_teams) {
          return ['status_code' => '422', 'message' => 'You need to assign all teams.'];
        }
        
        $this->teamRepoObj->updateMatches($teamsList,$teamsData,$data['data']);
      }

      $this->teamRepoObj->saveTeamManualRankingFromStandings($tournamentId, $ageGroupId, $teamsList);

      foreach ($teamData as $key => $value) {
          $team_id = str_replace('sel_', '', $value['name']);
          $this->teamRepoObj->assignGroup($team_id,$value['value'],$data['data'],$tempFixturesCount, $ageGroupId);
          # code...
      }
      $matchData = array('tournamentId'=>$tournamentId, 'ageGroupId'=>$ageGroupId);
      $matchresult =  $this->matchRepoObj->checkTeamIntervalForMatchesOnCategoryUpdate($matchData);
      $this->matchRepoObj->checkMaximumTeamIntervalForMatchesOnCategoryUpdate($matchData);

      return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
    }
    
    public function getAllTeamsGroup($data)
    {
        foreach ($data['data']['teamdata'] as $key => $value) {
            $team_id = str_replace('sel_', '', $value['name']);
            // $team_id = str_replace('sel_', '', $value['value']);
            $this->teamRepoObj->assignGroup($team_id,$value['value'],$data['data']);
            # code...
        }
        return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
    }

    /**
     * Delete Team.
     *
     * @param array $data
     *
     * @return [type]
     */
    public function deleteFromTournament($tournamentId,$ageGroup) {
        return  $this->teamRepoObj->deleteFromTournament($tournamentId,$ageGroup);
    }
    public function delete($data)
    {
        $data = $data->all();
        $data = $this->teamRepoObj->delete($data);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted'];
        }
    }
    public function getTeamsList($data)
    {
      // here first we get the key and based on that we listed out team details
      $data = $this->teamRepoObj->getTeamList($data);
      if ($data) {
            return ['status_code' => '200', 'data'=>$data,
            'message' => 'Data Successfully Deleted'];
        }
    }

    public function changeTeamName($data)
    {
      // here first we get the key and based on that we listed out team details
      $data = $this->teamRepoObj->changeTeamName($data['teamData']);
      if ($data) {
          return ['status_code' => '200', 'data'=>$data,
          'message' => 'Data Successfully Deleted'];
      }
    }

    public function getAllCompetitionTeamsFromFixture($data)
    {
        $data = $this->teamRepoObj->getAllCompetitionTeamsFromFixture($data['tournamentData']['competitionId']);
        if ($data) {
            return ['status_code' => '200', 'data' => $data];
        }

        return ['status_code' => '505', 'message' => 'Error in Data']; 
    }

    public function editTeamDetails($teamId)
    {
        return $this->teamRepoObj->editTeamDetails($teamId);
    }

    public function getAllCountries()
    {
        return $this->teamRepoObj->getAllCountries();
    }

    public function getAllTeamColors()
    {
        return $this->teamRepoObj->getAllTeamColors();
    }

    public function getAllClubs()
    {
        return $this->teamRepoObj->getAllClubs();      
    }

    public function updateTeamDetails($request, $teamId)
    {
      return $this->teamRepoObj->updateTeamDetails($request, $teamId);
    }
    public function checkTeamExist($request)
    {
      $data =  $this->teamRepoObj->checkTeamExist($request);
      if($data > 0) {

        return ['status' => 'true', 'data' => 'exist'];
      } else {
        return ['status' => 'false', 'data' => 'not exist'];

      }
    }
    public function resetAllTeams($request)
    {
        $data = $request->toArray();
        return $this->teamRepoObj->resetAllTeams($data);
    }

    public function getClubsByTournamentId($tournamentId)
    {
      return $this->teamRepoObj->getClubsByTournamentId($tournamentId);
    }

    public function getTeamsFairPlayData($teamData)
    {
      $data = $this->teamRepoObj->getTeamsFairPlayData($teamData);

      if ($data) {
        return ['status_code' => '200', 'data' => $data];
      }

      return ['status_code' => '505', 'message' => 'Error in Data'];
    }

    public function exportTeamFairPlayReport($data)
    {      
      $reportData = $this->queryForTeamsFairPlayReport($data);
      $dataArray = array();

      if(isset($data['report_download']) &&  $data['report_download'] == 'yes') {
        foreach ($reportData as $report) {
          $finalData = [
            $report->team_id,
            $report->name,
            $report->club_name,
            $report->country_name,
            $report->age_name,
            $report->total_red_cards == null ? 0 : $report->total_red_cards,
            $report->total_yellow_cards == null ? 0 : $report->total_yellow_cards
          ];
          array_push($dataArray, $finalData);
        }

        $otherParams = [
          'sheetTitle' => "fair_play_report",
          'sheetName' => "fair_play_report",
          'boldLastRow' => false
        ];

        $lableArray = [
          'TeamID', 'Team', 'Club', 'Country', 'Age category', 'Red cards', 'Yellow cards'
        ];

        \Laraspace\Custom\Helper\Common::toExcel($lableArray,$dataArray,$otherParams,'xlsx','yes');
      }

      if ($reportData) {
        return ['status_code' => '200', 'message' => '','data'=>$reportData];
      }
    }

    public function printTeamFairPlayReport($data)
    {
      $tournamentData = Tournament::where('id', '=', $data['tournament_id'])->select(DB::raw('CONCAT("'.$this->tournamentLogo.'", logo) AS tournamentLogo'))->first();
      $reportData = $this->queryForTeamsFairPlayReport($data);
      $date = new \DateTime(date('H:i d M Y'));

      $pdf = PDF::loadView('summary.fair_play_report',['data' => $reportData->all(), 'tournamentData' => $tournamentData])
            ->setPaper('a4')
            ->setOption('header-spacing', '5')
            ->setOption('header-font-size', 7)
            ->setOption('header-font-name', 'Open Sans')
            ->setOrientation('portrait')
            ->setOption('footer-html', route('pdf.footer'))
            ->setOption('header-right', $date->format('H:i d M Y'))
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20);
      
      return $pdf->inline('fair_play_report.pdf');
    }

    public function queryForTeamsFairPlayReport($data)
    {
      $reportQuery = Team::join('countries', function ($join) {
        $join->on('teams.country_id', '=', 'countries.id');
      })
      ->join('tournament_competation_template', 'tournament_competation_template.id', '=', 'teams.age_group_id')
      ->join('clubs', 'clubs.id', '=', 'teams.club_id')
      ->join('temp_fixtures', function($join) {
          $join->on('teams.id', '=', 'temp_fixtures.home_team')->orOn('teams.id', '=', 'temp_fixtures.away_team');
        })
      ->groupBy('teams.id')      
      ->where('teams.tournament_id',$data['tournament_id'])
      ->select('teams.*','teams.id as team_id', 'countries.name as country_name',
          'tournament_competation_template.group_name as age_name','tournament_competation_template.category_age as category_age','clubs.name as club_name', 
            DB::raw('SUM(CASE
              WHEN (temp_fixtures.home_team = teams.id) THEN temp_fixtures.home_yellow_cards ELSE temp_fixtures.away_yellow_cards
              END
              ) AS total_yellow_cards'),
            DB::raw('
              SUM(CASE
              WHEN (temp_fixtures.home_team = teams.id) THEN temp_fixtures.home_red_cards ELSE temp_fixtures.away_red_cards
              END
              ) AS total_red_cards'));

      if(isset($data['sel_ageCategory']) && $data['sel_ageCategory'] != null && $data['sel_ageCategory'] != ''){
        $reportQuery = $reportQuery->where('teams.age_group_id',$data['sel_ageCategory']);
      }

      if(isset($data['sort_by']) && $data['sort_by'] != '') {
        switch($data['sort_by']) {
          case 'team_id':
                $fieldName = 'teams.id';
                break;
          case 'name':
                $fieldName = 'teams.name';
                break;
          case 'club_name':
                $fieldName = 'clubs.name';
                break;
          case 'country_name':
                $fieldName = 'countries.name';
                break;
          case 'age_name':
                $fieldName = 'tournament_competation_template.group_name';
                break;
          case 'total_red_cards':
                $fieldName = 'total_red_cards';
                break;
          case 'total_yellow_cards':
                $fieldName = 'total_yellow_cards';
                break;
        }
        $reportQuery = $reportQuery->orderBy($fieldName, $data['sort_order']);
      }      

      $reportData = $reportQuery->get();
      return $reportData;
    }

    public function getTournamentTeamDetails($data)
    {
      $teamData = $this->teamRepoObj->getTournamentTeamDetails($data);
      if ($teamData) {
        return ['status_code' => '200','data'=>$teamData];
      }
    }

    public function printGroupsViewReport($data)
    {
      $tournamentTemplate = TournamentTemplates::find($data['tournamentTemplateId']);
      $jsonData = json_decode($tournamentTemplate->json_data, true);
      $jsonCompetitionFormatDataFirstRound = $jsonData['tournament_competation_format']['format_name'][0]['match_type'];

      $tournamentLogo = null;
      $tournamentDetail = Tournament::find($data['tournamentId']);
      if($tournamentDetail->logo != null) {
        $tournamentLogo = $this->tournamentLogo. $tournamentDetail->logo;
      }

      $groupsViewArray = [];
      foreach($jsonCompetitionFormatDataFirstRound as $round) {
        array_push($groupsViewArray, $round);
      }

      $teamsData = $this->teamRepoObj->getAllFromFilter($data);
      $ageCategoryDetail = TournamentCompetationTemplates::find($data['ageCategoryId']);
      $date = new \DateTime(date('H:i d M Y'));
      $pdf = PDF::loadView('teams_and_groups.group_detail_report',['data' => $data, 'groupsData' => $groupsViewArray, 'teamsData' => $teamsData, 'tournamentLogo' => $tournamentLogo, 'categoryName' => $ageCategoryDetail->group_name])
            ->setPaper('a4')
            ->setOption('header-spacing', '5')
            ->setOption('header-font-size', 7)
            ->setOption('header-font-name', 'Open Sans')
            ->setOrientation('portrait')
            ->setOption('footer-right', 'Page [page] of [toPage]')
            ->setOption('footer-font-size', 7)
            ->setOption('header-right', $date->format('H:i d M Y'))
            ->setOption('margin-top', 20)
            ->setOption('margin-bottom', 20);
      
      return $pdf->download($ageCategoryDetail->group_name. ' groups.pdf');
    }

    public function allocateTeamsAutomatically($data)
    {
      $ageCategoryTeams = $this->teamRepoObj->getAgeCategoryTeams($data);
      $template = $this->tournamentRepoObj->getTemplate($ageCategoryTeams['ageCategory']->tournament_template_id, $data['ageCategoryId']);
      $jsonObj = json_decode($template['json_data']);
      $jsonCompetationFormatDataFirstRound = $jsonObj->tournament_competation_format->format_name[0]->match_type;
      $availGroupTeam = [];
      $groups = [];
      $competId = NULL;

      foreach ($jsonCompetationFormatDataFirstRound as $group) {
        $splitGroupName = explode('-', $group->name);
        $competitionType = $splitGroupName[0];

        if($competitionType == 'PM' && $group->consider_in_team_assignment == "undefined") {
          return;
        }

        $groupName = '';
        if($competitionType == 'PM' && $group->consider_in_team_assignment == 1) {
          $groupName = $group->groups->actual_group_name + '-';
        } else {
          $groupName = $group->groups->group_name;
        }

        for($i = 1; $i <= $group->group_count; $i++ ){
          $availGroupTeam[] = $groupName . $i;
        }
      }
      $tempFixturesCount = TempFixture::where('tournament_id', $ageCategoryTeams['ageCategory']->tournament_id)
                                  ->where('age_group_id', $data['ageCategoryId'])
                                  ->where(function($query){
                                    $query->orWhereNotNull('hometeam_score')
                                          ->orWhereNotNull('awayteam_score');
                                  })
                                  ->get()
                                  ->count();
      $tournamentData = [];
      $tournamentData['tournament_id'] = $ageCategoryTeams['ageCategory']->tournament_id;
      foreach ($ageCategoryTeams['ageCategoryTeams'] as $team) {
        $randomKey = array_rand($availGroupTeam);
        $availGroupTeam[array_rand($availGroupTeam)];
        $this->teamRepoObj->assignGroup($team->id, $availGroupTeam[$randomKey], $tournamentData, $tempFixturesCount, $data['ageCategoryId']);
        unset($availGroupTeam[$randomKey]);
      }
      return ['status_code' => '200', 'message' => 'Data Successfully Updated'];
    }
}