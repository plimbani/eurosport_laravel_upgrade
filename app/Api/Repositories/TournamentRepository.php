<?php
namespace Laraspace\Api\Repositories;

use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use JWTAuth;
use Laraspace\Models\User;
use Laraspace\Models\Competition;
use Laraspace\Models\Pitch;
use Laraspace\Models\PitchAvailable;
use Laraspace\Models\PitchBreaks;
use Laraspace\Models\PitchUnavailable;
use Laraspace\Models\Referee;
use Laraspace\Models\Team;
use Laraspace\Models\TempFixture;
use Laraspace\Models\Tournament;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\TournamentContact;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\UserFavourites;
use Laraspace\Models\Venue;
use Laraspace\Models\Website;
use Laraspace\Models\AgeCategoryDivision;
use Laraspace\Models\Position;
use Laraspace\Models\MatchStanding;
use Laraspace\Models\TeamManualRanking;
use Laraspace\Models\TournamentClub;
use Laraspace\Models\TournamentUser;

class TournamentRepository
{
    public $slug;
    public function __construct()
    {
        $this->matchRepoObj = new \Laraspace\Api\Repositories\MatchRepository();
        $this->tournamentLogo = getenv('S3_URL') . '/assets/img/tournament_logo/';
    }
    public function getTournamentsByStatus($tournamentData)
    {
        $status = $tournamentData['status'];
        return Tournament::where('status', $status)->get();
    }
    public function getTournamentsBySlug($tournamentData)
    {
        $slug = $tournamentData;
        return Tournament::where('slug', $slug)->first();
    }
    public function getAll($status = '', $user = null)
    {
        if ($status == '') {
            $data = Tournament::
                select('tournaments.*',
                \DB::raw('IF(tournaments.logo is not null,CONCAT("' . $this->tournamentLogo . '", tournaments.logo),"" ) as tournamentLogo'));
        } else {
            $data = Tournament::whereIn('tournaments.status', array('Published','Preview'))
                ->select('tournaments.*',
                    \DB::raw('IF(tournaments.logo is not null,CONCAT("' . $this->tournamentLogo . '", tournaments.logo),"" ) as tournamentLogo'));

        }

        if ($user) {
            $tournaments = $user->tournaments()->pluck('id');
            $data        = $data->whereIn('id', $tournaments);
        }
        $data = $data->orderBy('name', 'asc')->get();

        return $data;
        /*if($status == '') {
    return Tournament::get();
    }
    else{
    return Tournament::where('status','=','Published')->get();
    }*/
    }
    public function getAuthUserCreatedTournaments($status = '')
    {
        if ($status == '') {
            $data = Tournament::
                select('tournaments.*',
                \DB::raw('IF(tournaments.logo is not null,CONCAT("' . $this->tournamentLogo . '", tournaments.logo),"" ) as tournamentLogo'))
                ->get();
        } else {
            $data = Tournament::whereIn('tournaments.status', array('Published','Preview'))
                ->select('tournaments.*',
                    \DB::raw('IF(tournaments.logo is not null,CONCAT("' . $this->tournamentLogo . '", tournaments.logo),"" ) as tournamentLogo')
                )
                ->get();

        }
        return $data;
        /*if($status == '') {
    return Tournament::get();
    }
    else{
    return Tournament::where('status','=','Published')->get();
    }*/
    }
    public function getTemplate($tournamentTemplateId, $ageCategoryId)
    {

        $tournamentTemplateData              = [];
        $tournamentTemplateData['json_data'] = '';        
        $tempFixtures = DB::table('temp_fixtures')->where('age_group_id', $ageCategoryId)
            ->leftjoin('venues', 'temp_fixtures.venue_id', '=', 'venues.id')
            ->leftjoin('pitches', 'temp_fixtures.pitch_id', '=', 'pitches.id')
            ->select(['temp_fixtures.match_number', 'temp_fixtures.display_match_number', 'temp_fixtures.home_team', 'temp_fixtures.home_team_name', 'temp_fixtures.away_team', 'temp_fixtures.away_team_name', 'venues.name as venue_name', 'pitches.pitch_number as pitch_name', 'pitches.size as pitch_size', 'temp_fixtures.is_scheduled as is_scheduled', 'temp_fixtures.match_datetime as match_datetime','temp_fixtures.hometeam_score','temp_fixtures.awayteam_score'])
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
        if($tournamentTemplateId != NULL) {
            $tournamentTemplate                  = TournamentTemplates::find($tournamentTemplateId);
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
        $tournamentTemplateData['graphicHtml'] = view('template.graphic', [
            'fixtures' => $tempFixtures,
            'templateData' => $jsonData,
            'assignedTeams' => $assignedTeams,
            'categoryAge' => $tournamentCompetitionTemplate->category_age,
            'groupName' => $tournamentCompetitionTemplate->group_name,
            'allMatches' => $allMatches,
        ])->render();

        return $tournamentTemplateData;
    }
    public function getAllTemplates($data = array())
    {
        if (is_array($data) && count($data['tournamentData']) > 0) {
            // TODO: need to Add
            return TournamentTemplates::get();
        } else {
            // here we modified the data
            return TournamentTemplates::get();
        }

    }
    public function getAllTemplatesFromMatches($data = array())
    {
        if (is_array($data) && count($data['tournamentData']) > 0) {
            if (($data['tournamentData']['tournament_format'] == 'advance' || $data['tournamentData']['tournament_format'] == 'festival') && $data['tournamentData']['minimum_matches'] != '' && $data['tournamentData']['total_teams'] != '') {
                return TournamentTemplates::where(['total_teams' => $data['tournamentData']['total_teams'], 'minimum_matches' => $data['tournamentData']['minimum_matches']])->where('editor_type', $data['tournamentData']['tournament_format'])->where('is_latest', 1)->orderBy('name')->get();
            } else if($data['tournamentData']['tournament_format'] == 'basic' && $data['tournamentData']['competition_type'] == 'knockout' && $data['tournamentData']['group_size'] != '' && $data['tournamentData']['total_teams'] != '') {
                return TournamentTemplates::where(['total_teams' => $data['tournamentData']['total_teams'], 'total_groups' => $data['tournamentData']['group_size']])->where('editor_type', $data['tournamentData']['competition_type'])->where('is_latest', 1)->orderBy('name')->get();
            }
        } else {
            return;
        }
    }

    /**
     * Generate slug
     *
     */
    public function generateSlug($title)
    {
        $slug      = Str::slug($title);
        $slugCount = count(Tournament::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get());
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

    private function getTournamentDays($startDate, $endDate)
    {
        $startDate = str_replace('/', '-', $startDate);
        $endDate   = str_replace('/', '-', $endDate);

        $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
        // TODO add one day
        $days = $days + 1;
        return $days;
    }

    public function tournamentSummary($tournamentId)
    {
        $isMobileUsers = \Request::header('IsMobileUser');
        // here we put validation for tournament id is exist
        $summaryData = array();

        // we only consider relevent table data
        $locationData = Venue::where('tournament_id', $tournamentId)->get();

        // we get Multiple LocationIds
        // TODO:--
        $tempData = array();
        if (count($locationData) > 0) {
            foreach ($locationData as $location) {
                $tempData['locationData'][] = $location;
            }

            $summaryData['locations'] = $tempData['locationData'];

        }
        $tournamentCompetaionTemplateData = TournamentCompetationTemplates::where('tournament_id', $tournamentId)->get();

        $summaryData['tournament_teams']   = 0;
        $summaryData['tournament_matches'] = 0;

        if (count($tournamentCompetaionTemplateData) > 0) {
            foreach ($tournamentCompetaionTemplateData as $tournamentData) {
                // here we consider whole string for total teams
                $tempData['total_teams'][]  = $tournamentData['disp_format_name'];
                $tempData['total_match'][]  = $tournamentData['total_match'];
                $tempData['category_age'][] = $tournamentData['category_age'];
            }
            $summaryData['tournament_matches'] = array_sum($tempData['total_match']);
            $summaryData['tournament_teams']   = array_sum($tempData['total_teams']);

            $summaryData['tournament_groups'] = implode(' , ', array_unique($tempData['category_age']));
        }
        // TODO: Add Some Code For Mobile Data

        if ($isMobileUsers != '') {

            $tournamentPitches = Pitch::with('pitchAvailability')->where('tournament_id', $tournamentId)->get();
            $stage_start_time  = array();
            foreach ($tournamentPitches as $tournamentPitch) {
                if ($tournamentPitch->pitchAvailability) {
                    foreach ($tournamentPitch->pitchAvailability as $pitchAvailibility) {
                        if ($pitchAvailibility->stage_no == 1) {
                            $stage_start_time[] = $pitchAvailibility->stage_start_time;
                            break;
                        }
                    }
                }
            }
            // Get minimum Value and Set it
            $summaryData['tournament_start_time'] = min($stage_start_time);
            $summaryData['tournament_pitches']    = count($tournamentPitches);
            unset($stage_start_time);
            $peopleData = TournamentContact::with('tournaments')->where('tournament_id', $tournamentId)->get();
            if (count($peopleData) > 0) {
                $summaryData['tournament_contact'] = $peopleData[0];
            }

        } else {
            $tournamentPitch                   = Pitch::where('tournament_id', $tournamentId)->get();
            $summaryData['tournament_pitches'] = count($tournamentPitch);
            $peopleData                        = TournamentContact::where('tournament_id', $tournamentId)->get();
            if (count($peopleData) > 0) {
                $summaryData['tournament_contact'] = $peopleData[0];
            }
        }

        $summaryData['tournament_age_categories'] = count($tournamentCompetaionTemplateData);

        // TODO: Referee is Added
        $refereeCount                       = Referee::where(['tournament_id' => $tournamentId])->count();
        $summaryData['tournament_referees'] = $refereeCount;

        // TODO: country  is remaining depends on team
        $teamsCountries = Team::join('countries', function ($join) {
            $join->on('teams.country_id', '=', 'countries.id');
        })
            ->where('teams.tournament_id', $tournamentId)
            ->select('countries.name as country_name')
            ->get();
        $summaryData['tournament_countries'] = '';
        if (count($teamsCountries) > 0) {
            foreach ($teamsCountries as $teamCountry) {
                $tempData['tournament_countries'][] = $teamCountry['country_name'];
            }

            $summaryData['tournament_countries'] = implode(' , ', array_unique($tempData['tournament_countries']));
        }

        $summaryData['tournament_detail'] = Tournament::find($tournamentId);
        
        return $summaryData;
    }
    public function tournamentReport($data)
    {
        $matchData = TempFixture::where('temp_fixtures.tournament_id', $data['tournament_id'])
            ->where('venue_id', $data['location'])
            ->where('pitch_id', $data['pitch'])
            ->where('match_datetime', '>=', Carbon::parse($data['start_date'])->format('m/d/Y'))
            ->where('match_datetime', '<=', Carbon::parse($data['end_date'])->format('m/d/Y'))
            ->where('pitch_id', $data['pitch'])
        // ->Join('tournament_competation_template', 'tournament_competation_template.tournament_id', '=', 'tournament.id')
            ->get();

        return $matchData;

    }
    public function updateStatus($tournamentData)
    {
        $newdata           = array();
        $newdata['status'] = $tournamentData['status'];
        $tournamentId      = $tournamentData['tournamentId'];

        if($tournamentData['status'] == "Unpublished") {
            Website::where('linked_tournament',$tournamentId)->update(['linked_tournament' => NULL]);
        }

        $tournament = Tournament::find($tournamentId);
        $tournament->status = $tournamentData['status'];

        if(($tournamentData['status'] == "Published" || $tournamentData['status'] == "Preview") && $tournament->is_published_preview_once === 0) {
            $switchDefaultTournament = $tournamentData['switchDefaultTournament'];
            $userFavourites = UserFavourites::where('tournament_id', $tournament->duplicated_from)->get();
            foreach ($userFavourites as $userFavourite) {
                $copiedUserFavourite = $userFavourite->replicate();
                $copiedUserFavourite->tournament_id = $tournament->id;
                if($switchDefaultTournament == 0) {
                    $copiedUserFavourite->is_default = 0;
                }
                if($switchDefaultTournament == 1 && $copiedUserFavourite->is_default == 1) {
                    UserFavourites::where('user_id', '=', $copiedUserFavourite->user_id)->update(['is_default' => 0]);
                }
                $copiedUserFavourite->save();
            }
            $tournament->is_published_preview_once = 1;
        }

        $tournament->save();

        return true;
    }
    public function tournamentFilter($tournamentData)
    {
        // dd($tournamentData);
        $tournamentId = $tournamentData['tournamentData']['tournamentId'];
        $key          = $tournamentData['tournamentData']['keyData'];
        $resultData   = array();
        // now here we fetch data for specefic key
        if ($tournamentData['tournamentData']['type'] == 'teams' || $tournamentData['tournamentData']['type'] == 'scheduleResult') {
            $reportQuery = Team::where('teams.tournament_id', '=', $tournamentId);
            $token = \JWTAuth::getToken();
            switch ($key) {
                case 'team':
                    $resultData = $reportQuery->select('id', 'name as name')
                        ->get();
                    break;
                case 'country':
                    $resultData = $reportQuery->join('countries', 'countries.id', '=', 'teams.country_id')
                        ->select('countries.id as id', 'countries.name as name')
                        ->distinct('name')
                        ->get();
                    break;
                case 'age_category':
                    $resultData = TournamentCompetationTemplates::where('tournament_id', $tournamentId)
                        ->select('id', \DB::raw("CONCAT(group_name, ' (', category_age,')') AS name"), 'tournament_template_id')
                        ->get();
                    break;
                case 'location':
                    $resultData = Venue::where('tournament_id', $tournamentId)
                        ->select('id', 'name')
                        ->get();
                    //echo $resultData;
                    break;
                case 'competation_group':
                    // $resultData = TournamentCompetationTemplates::with('Competition')->where('tournament_id', $tournamentId)
                    //     ->select('id', \DB::raw("CONCAT(group_name, ' (', category_age,')') AS name"), 'tournament_template_id');

                    if(!$token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {
                        $resultData = TournamentCompetationTemplates::with('Competition')->where('tournament_id', $tournamentId)
                        ->select('id', \DB::raw("CONCAT(group_name, ' (', category_age,')') AS name"), 'tournament_template_id')->whereHas('scheduledFixtures');
                    } else {
                        $resultData = DB::table('competitions')
                        ->where('competitions.tournament_id', $tournamentId)
                        ->leftjoin('tournament_competation_template','tournament_competation_template.id', '=', 'competitions.tournament_competation_template_id')
                        ->leftjoin('age_category_divisions', 'competitions.age_category_division_id', '=', 'age_category_divisions.id');

                        $resultData->select('competitions.*','tournament_competation_template.group_name', 'age_category_divisions.name as divisionName', 'age_category_divisions.id as divisionId', \DB::raw("CONCAT(tournament_competation_template.group_name, ' (', tournament_competation_template.category_age,')') AS age_category_name"));

                        $resultData = $resultData->get();

                        $finalData = [];
                        $ageCategoryData = [];

                        foreach ($resultData as $data) {
                            $finalData[$data->tournament_competation_template_id]['name'] = $data->age_category_name;
                            if($data->age_category_division_id != '') {
                                $finalData[$data->tournament_competation_template_id]['groups']['divisions'][$data->divisionId]['name'] = $data->divisionName;
                                $finalData[$data->tournament_competation_template_id]['groups']['divisions'][$data->divisionId]['rounds'][$data->competation_round_no][] = $data;
                            } else {
                                $finalData[$data->tournament_competation_template_id]['groups']['round_robin'][$data->competation_round_no][] = $data;
                            }
                        }

                        return $finalData;
                    }
                    $resultData = $resultData->get();
            }
        } else {

            $reportQuery = TempFixture::where('temp_fixtures.tournament_id', '=', $tournamentId);
            switch ($key) {
                case 'location':
                    $resultData = Venue::where('tournament_id', $tournamentId)
                        ->select('id', 'name')
                        ->get();
                    //echo $resultData;
                    break;
                /*case 'location' :
                $resultData = $reportQuery->join('venues','venues.id','=','temp_fixtures.venue_id')
                ->select('venues.id as id','venues.name as name')
                ->distinct('name')
                ->get();
                //echo $resultData;
                break;*/
                case 'age_category':
                    $resultData = $reportQuery->join('competitions', 'competitions.id', '=', 'temp_fixtures.competition_id')
                        ->join('tournament_competation_template', 'competitions.tournament_competation_template_id', '=', 'tournament_competation_template.id')
                        ->select('tournament_competation_template.id as id', \DB::raw("CONCAT(tournament_competation_template.group_name, ' (', tournament_competation_template.category_age,')') AS name"))
                        ->distinct('name')
                        ->get();
                    break;
            }
        }

        return $resultData;
    }

    public function getAllCategory($tournamentId)
    {
        // dd($data);
        return TournamentCompetationTemplates::where('tournament_id', $tournamentId)->select('id', 'group_name', 'category_age')->get();
    }
    public function getUserDefaultLoginTournament($data)
    {

        $userData = UserFavourites::where('users_favourite.user_id', '=', $data['user_id'])
            ->where('users_favourite.is_default', '=', '1')
            ->leftJoin('tournaments', 'tournaments.id', '=', 'users_favourite.tournament_id')
            ->select('tournaments.*', 'users_favourite.*',
                \DB::raw('CONCAT("' . $this->tournamentLogo . '", tournaments.logo) AS tournamentLogo'))
            ->get()
            ->first();
        if (count($userData) > 0) {
            return $userData;
        }
    }
    private function getTournamentPitchStartTime($tournamentId)
    {
        // here we query the pitch_availibility table for getting the start time for tournament
        /* $pitches = PitchAvailable::whereIn('tournament_id',$tournamentId)
        ->select(\DB::raw('CONCAT(stage_start_date," ",stage_start_time) as TournamentStartTime'),'tournament_id as TId')
        ->orderBy('stage_start_time','asc')
        ->orderBy('stage_start_date','asc')
        ->get()->first(); */
        // TODO : Change the code to find first schedule match for that tournament

        $pitches = TempFixture::whereIn('tournament_id', $tournamentId)
            ->whereNotNull('match_datetime')
            ->select('match_datetime as TournamentStartTime',
                'temp_fixtures.tournament_id as TId')
            ->orderBy('temp_fixtures.match_datetime', 'asc')
            ->get()
            ->unique('TId');
        if ($pitches) {
            return $pitches->toArray();
        } else {
            return '';
        }

    }
    public function getUserLoginFavouriteTournament($data)
    {
        \Log::info('getUserLoginFavouriteTournament:' .json_encode($data));
        //$url = getenv('S3_URL').'/assets/img/tournament_logo/';
        // Now here we attach the tournament Start Date Seperately for check the first started match
        $userData = UserFavourites::where('users_favourite.user_id', '=', $data['user_id'])
            ->whereIn('tournaments.status', array('Published','Preview'))
            //->where('tournaments.status', '=', 'Published')
            ->where('tournaments.deleted_at', '=', NULL)
            ->leftJoin('tournaments', 'tournaments.id', '=', 'users_favourite.tournament_id')
            ->leftJoin('tournament_contact', 'tournaments.id', '=', 'tournament_contact.tournament_id')
            ->select('tournaments.*',
                'users_favourite.*',
                'tournaments.id as TournamentId',
                'tournaments.start_date as TournamentStartTime',
                'tournament_contact.first_name',
                'tournament_contact.last_name',
                'tournament_contact.telephone',
                'tournament_contact.email',
                \DB::raw('CONCAT("' . $this->tournamentLogo . '", tournaments.logo) AS tournamentLogo'))
            ->get()->toArray();
        $tournament_ids = array();
        if (count($userData) > 0) {
            foreach ($userData as $tournamentData) {
                $tournament_ids[] = $tournamentData['TournamentId'];
            }

            // now call function and send tournament ids
            $tournamentStartTimeArr = $this->getTournamentPitchStartTime($tournament_ids);

            foreach ($userData as $index => $userData1) {

                if ($tournamentStartTimeArr) {
                    foreach ($tournamentStartTimeArr as $key => $tournamentTime) {
                        if ($userData1['TournamentId'] == $tournamentTime['TId']) {
                            $userData[$index]['TournamentStartTime'] = date('Y-m-d H:i:s', strtotime($tournamentTime['TournamentStartTime']));
                        }
                    }
                }
                /*else {
            //return $userData;
            }*/
            }

            return $userData;

        } else {
            return array();
        }

        // Now here we calculate
        /*if(count($userData) > 0) {
    return $userData;
    } else {
    return array();
    } */
    }
    public function getTournamentClub($data)
    {
        // Find teams for that tournament and clubs for that teams

        $url      = getenv('S3_URL');
        $clubData = Team::where('teams.tournament_id', '=', $data['tournament_id'])
            ->whereNotNull('teams.group_name')
            ->leftJoin('clubs', 'clubs.id', '=', 'teams.club_id')
            ->leftjoin('countries', 'countries.id', '=', 'teams.country_id')
            ->select('clubs.id as ClubId', 'clubs.name as clubName', 'countries.id as countryId', 'countries.name as CountryName',
                \DB::raw('CONCAT("' . $url . '", countries.logo ) AS CountryLogo')
            );
            
        if(app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true") {
          $clubData = $clubData->where(function($q) use($data) {
            return $q->whereHas('homeFixtures', function($q1) use($data) {
              $q1->where('is_scheduled', 1)->where('tournament_id', $data['tournament_id']);
            })->orWhereHas('awayFixtures', function($q2) use($data) {
              $q2->where('is_scheduled', 1)->where('tournament_id', $data['tournament_id']);
            });
          });
        }
        
        $clubData = $clubData->groupBy('clubs.id', 'countries.id')->get();

        return (count($clubData) > 0) ? $clubData : 0;
    }

    public function getCategoryCompetitions($data)
    {
        $token = \JWTAuth::getToken();
        $categoryCompetitions = Competition::with('AgeCategoryDivision')->where('tournament_competation_template_id', $data['ageGroupId']);
        if (isset($data['competationType'])) {
            $categoryCompetitions = $categoryCompetitions->where('competation_type', $data['competationType']);
        }
        if (isset($data['competationRoundNo'])) {
            $categoryCompetitions = $categoryCompetitions->where('competation_round_no', $data['competationRoundNo']);
        }

        if(!$token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {
            $categoryCompetitions = $categoryCompetitions->whereHas('scheduledFixtures');
        }

        $categoryCompetitions = $categoryCompetitions->get();

        if ( !isset($data['fromDrawList']))
        {
            return $categoryCompetitions;
        }

        $categoryCompetitions = $categoryCompetitions->toArray();
        $divisionsData = [];
        $data = [];
        foreach ($categoryCompetitions as $key => $value) {
            if ( isset($value['age_category_division']) )
            {
                $divisionsData[$value['age_category_division_id'].'|'.$value['age_category_division']['name']][$value['competation_round_no']][] = $value;

                unset($categoryCompetitions[$key]);  
            }
        }

        $data['round_robin'] = $categoryCompetitions;
        $data['division'] = $divisionsData;
        
        return $data;
    }

    public function getAllPublishedTournaments($data)
    {
        $publishedTournaments = Tournament::where('status', 'Published')->get();
        return $publishedTournaments;
    }

    public function getFilterDropDownData($data)
    {
        $tournamentId = $data['tournamentId'];
        $filterBy     = $data['filterBy'];
        $token = \JWTAuth::getToken();

        $resultData = array();
        switch ($filterBy) {
            case 'team':
                $resultData = Team::where('teams.tournament_id', $tournamentId)->orderBy('name', 'asc')->select('id', 'name')->get();
                break;
            case 'category_and_competition':
                $resultData = TournamentCompetationTemplates::with('Competition')->where('tournament_id', $tournamentId)
                    ->select('id', \DB::raw("CONCAT(group_name, ' (', category_age,')') AS name"), 'tournament_template_id');
                if(!$token || (app('request')->header('ismobileuser') && app('request')->header('ismobileuser') == "true")) {
                    $resultData = $resultData->whereHas('scheduledFixtures');
                }
                $resultData = $resultData->get();
                break;
            case 'location':
                $resultData = Venue::where('tournament_id', $tournamentId)->select('id', 'name')->get();
                break;
        }

        return $resultData;
    }

    public function getCompetitionAndPitchDetail($data)
    {
        $ageCategoryDetail = TournamentCompetationTemplates::with(['Competition' => function($query) {
            return $query->doesnthave('scheduledFixtures');
        }])
        ->where('id', $data['ageCategoryId'])
        ->first();

        $pitches = Pitch::where('tournament_id', $data['tournamentId'])->where('size', $ageCategoryDetail->pitch_size)->get();

        $alreadyScheduledMatchesCount = TempFixture::where('age_group_id', $data['ageCategoryId'])->where('is_scheduled', 1)->get()->count();

        return ['ageCategoryDetail' => $ageCategoryDetail, 'pitches' => $pitches, 'alreadyScheduledMatchesCount' => $alreadyScheduledMatchesCount];
    }

    public function getAllPitchesWithDays($pitchId)
    {
        $pitchAvailability = PitchAvailable::where('pitch_id', $pitchId)->select('stage_no', 'stage_start_date', 'stage_start_time', 'stage_end_time')->get()->toArray();

        return $pitchAvailability;
    }

    public function processPitchAvailibity($nextPitchIndex, $allPitchIds, &$pitchAvailableTime, $matchTime, &$roundWiseLastMatchDateTime, $match, &$matchScheduleArray, $data, $allPitches)
    {
        $reCheckPitchOrder = false;
        for ($pitchIndex = $nextPitchIndex; $pitchIndex < count($allPitchIds); $pitchIndex++) {
            $pitchId = $allPitchIds[$pitchIndex];
            $availability = $pitchAvailableTime[$pitchId];
            $i = 0;
            $startTimeStamp = null;
            $isMatchScheduledFlag = false;
            foreach ($availability as $timestamp => $value) {
                if ($matchTime == $i) {
                    $startTimeStamp = Carbon::createFromTimestamp($startTimeStamp);
                    $endTimeStamp = Carbon::createFromTimestamp($timestamp);
                    if ($data['competition'] == 'all') {
                        $round = $match->competation_round_no;
                        if(!isset($roundWiseLastMatchDateTime[$match->age_group_id][$round]) || $endTimeStamp->greaterThan($roundWiseLastMatchDateTime[$match->age_group_id][$round])) {
                            $roundWiseLastMatchDateTime[$match->age_group_id][$round] = $endTimeStamp;
                        }
                    }

                    $matchScheduleArray[$match->id] = array(
                        'match_start_time' => clone ($startTimeStamp),
                        'match_end_time' => clone ($endTimeStamp),
                        'pitch_id' => $pitchId,
                        'venue_id' => $allPitches[$pitchId]->venue_id,
                    );

                    while ($startTimeStamp <= $endTimeStamp) {
                        $pitchAvailableTime[$pitchId][$startTimeStamp->timestamp] = 0;
                        if($startTimeStamp->timestamp === $endTimeStamp->timestamp) {
                            $pitchAvailableTime[$pitchId][$startTimeStamp->timestamp] = 2;
                        }
                        $startTimeStamp->addMinute(1);
                    }
                    $isMatchScheduledFlag = true;
                }

                if ($i < $matchTime && ($value == 1 || $value == 2)) {
                    $canMatchBeSchedule = true;
                    if ($startTimeStamp == null) {
                        if (!isset($pitchAvailableTime[$pitchId][Carbon::createFromTimestamp($timestamp)->addMinute($matchTime)->timestamp])) {
                            continue;
                        }

                        $matchStartTimeStamp = Carbon::createFromTimestamp($timestamp);
                        $matchEndTimeStamp = (clone ($matchStartTimeStamp))->addMinute($matchTime);

                        if ($data['competition'] == 'all') {
                            $round = $match->competation_round_no;
                            $roundArray = explode(" ", $round);
                            $roundNumber = intval($roundArray[1]);
                            if($roundNumber > 0) {
                                $previousRoundNumber = $roundNumber - 1;
                                $previousRound = $roundArray[0] . ' ' . $previousRoundNumber;
                                if(isset($roundWiseLastMatchDateTime[$match->age_group_id][$previousRound])) {
                                    if($matchStartTimeStamp->lessThan($roundWiseLastMatchDateTime[$match->age_group_id][$previousRound])) {
                                        $pitchAvailableTime[$pitchId][$timestamp] = -1 * $pitchAvailableTime[$pitchId][$timestamp];
                                        $canMatchBeSchedule = false;
                                        $reCheckPitchOrder = true;
                                    }
                                }
                            }
                        }

                        if ($canMatchBeSchedule == true) {
                            if($reCheckPitchOrder) {
                                break;
                            }
                            $startTimeStamp = $timestamp;
                        }
                    }
                    if($canMatchBeSchedule == true) {
                        $i++;
                        continue;
                    }
                }
                $i = 0;
                $startTimeStamp = null;
                if ($isMatchScheduledFlag == true) {
                    break;
                }
            }
            if($reCheckPitchOrder) {
                break;
            }
            if ($isMatchScheduledFlag == true) {
                if($pitchIndex === (count($allPitchIds) - 1)) {
                    $nextPitchIndex = 0;
                } else {
                    $nextPitchIndex++;
                }
                break;
            }
        }
        return $reCheckPitchOrder;
    }

    public function sortPitchesBasedOnAvailibility(&$pitchAvailableTime, &$allPitchIds, $userSelectedPitchOrder)
    {
        $userSelectedPitchOrderFlipped = array_flip($userSelectedPitchOrder);
        $pitchIdWiseFirstAvailability = [];
        foreach($pitchAvailableTime as $pitchId => $pitchAvailability) {
            $previousTimestamp = null;
            foreach ($pitchAvailability as $timestamp => $value) {
                if($value == 1) {
                    $pitchIdWiseFirstAvailability[$pitchId] = ($previousTimestamp !== null && $pitchAvailableTime[$pitchId][$previousTimestamp] == 2) ? $previousTimestamp : $timestamp;
                    $userSelectedPitchOrderFlipped[$pitchId] = ($previousTimestamp !== null && $pitchAvailableTime[$pitchId][$previousTimestamp] == 2) ? $previousTimestamp : $timestamp;
                    break;
                }
                $previousTimestamp = $timestamp;
            }
        }
        asort($pitchIdWiseFirstAvailability);
        $pitchIdWiseFirstAvailabilityTimestamps = array_values(array_unique(array_values($pitchIdWiseFirstAvailability)));
        
        $pitchIdWiseSortedByFirstAvailability = [];
        foreach($pitchIdWiseFirstAvailabilityTimestamps as $timestamp) {
            $timestampArray = array_filter($pitchIdWiseFirstAvailability, function($value) use($timestamp) {
                return $value == $timestamp;
            });
            $orderedTimestampArray = array_intersect_key($userSelectedPitchOrderFlipped, $timestampArray);
            $pitchIdWiseSortedByFirstAvailability = array_replace($pitchIdWiseSortedByFirstAvailability, $orderedTimestampArray);
        }

        $pitchAvailableTime = array_replace($pitchIdWiseSortedByFirstAvailability, $pitchAvailableTime);
        $allPitchIds = array_keys($pitchAvailableTime);
    }

    public function revertPitchAvailibilityRoundChanges(&$pitchAvailableTime)
    {
        foreach($pitchAvailableTime as $pitchId => $pitchAvailability) {
            foreach ($pitchAvailability as $timestamp => $value) {
                if($value === -1 || $value === -2) {
                    $pitchAvailableTime[$pitchId][$timestamp] = abs($pitchAvailableTime[$pitchId][$timestamp]);
                }
            }
        }
    }

    /**
     * Update competition display name.
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function updateCompetitionDisplayName($data)
    {
        $competition = Competition::where('id', $data['competitionData']['id'])
                    ->where('tournament_id', $data['competitionData']['tournament_id'])
                    ->update(['display_name' => $data['competitionData']['display_name']]);

        $competitionData = Competition::where('tournament_competation_template_id', $data['competitionData']['tournament_competation_template_id'])
                                        ->where('tournament_id', $data['competitionData']['tournament_id'])
                                        ->get();
                                        
        return ['data' => $competitionData, 'status' => 'Success', 'message' => 'Competition name has been updated.'];
    }

    /**
    * Update category division display name.
    */
    public function updateCategoryDivisionName($data)
    {
        return AgeCategoryDivision::where('tournament_id', $data['tournamentData']
                                    ['tournament_id'])
                                    ->where('tournament_competition_template_id', 
                                    $data['tournamentData']['currentAgeCategoryId'])
                                    ->where('id', $data['tournamentData']['divisionId'])
                                    ->update(['name' => $data['tournamentData']['categoryDivisionName']]);
    }
}
