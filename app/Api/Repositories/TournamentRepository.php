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
        if (is_array($data) && count($data['tournamentData']) > 0 && $data['tournamentData']['minimum_matches'] != '' && $data['tournamentData']['total_teams'] != '') {
            return TournamentTemplates::where(['total_teams' => $data['tournamentData']['total_teams'], 'minimum_matches' => $data['tournamentData']['minimum_matches']])->where('editor_type', $data['tournamentData']['tournament_format'])->where('is_latest', 1)->orderBy('name')->get();
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

    public function create($data)
    {
        // Save Tournament Data
        $newdata                  = array();
        $newdata['name']          = $data['name'];
        $newdata['maximum_teams'] = $data['maximum_teams'];
        $newdata['start_date']    = $data['start_date'] ? $data['start_date'] : '';
        $newdata['end_date']      = $data['end_date'] ? $data['end_date'] : '';
        $newdata['website']       = $data['website'] ? $data['website'] : '';
        $newdata['facebook']      = $data['facebook'] ? $data['facebook'] : '';
        $newdata['twitter']       = $data['twitter'] ? $data['twitter'] : '';

        // For New One We set Status as Unpublished

        if ($data['image_logo'] != '') {
            $newdata['logo'] = $data['image_logo'];
        } else {
            $newdata['logo'] = null;
        }
        // Now here we Save it For Tournament
        $imageChanged = true;
        if (isset($data['tournamentId']) && $data['tournamentId'] != 0) {
            // Update Touranment Table Data
            $tournamentId          = $data['tournamentId'];
            $newdata['start_date'] = Carbon::createFromFormat('d/m/Y', $newdata['start_date']);
            $newdata['end_date']   = Carbon::createFromFormat('d/m/Y', $newdata['end_date']);
            // here we check for image Logo is exist
            // means nothing need to updated it

            //update dates in pitch available
            $tournamentDetail = Tournament::where('id', $tournamentId)->first();
            $oldSdate         = Carbon::createFromFormat('d/m/Y', $tournamentDetail->start_date);
            $dateDiff         = $oldSdate->diffInDays($newdata['start_date']);
            \Log::info("Pitch capacity date check --- start");
            \Log::info("Date diff - " . $dateDiff);
            if ($dateDiff > 0) {
                $pitchCnt          = Pitch::where('tournament_id', $tournamentId)->get()->count();
                $pitchAvailableAll = PitchAvailable::where('tournament_id', $tournamentId)->get();
                \Log::info("Tournament id:" . $tournamentDetail->id);
                \Log::info($oldSdate);
                \Log::info($newdata['start_date']);
                if ($pitchCnt > 0) {
                    foreach ($pitchAvailableAll as $pitchAvail) {
                        if ($oldSdate->lt($newdata['start_date'])) {
                            \Log::info("Date found less than");
                            $newStageStartdate    = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_start_date'])->addDays($dateDiff);
                            $newStageContinuedate = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_continue_date'])->addDays($dateDiff);
                            $newStageEnddate      = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_end_date'])->addDays($dateDiff);
                        }
                        if ($oldSdate->gt($newdata['start_date'])) {
                            \Log::info("Date found greater than");
                            $newStageStartdate    = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_start_date'])->subDays($dateDiff);
                            $newStageContinuedate = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_continue_date'])->subDays($dateDiff);
                            $newStageEnddate      = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_end_date'])->subDays($dateDiff);
                        }
                        \Log::info($newStageStartdate);
                        \Log::info($newStageContinuedate);
                        \Log::info($newStageEnddate);
                        PitchAvailable::where('id', $pitchAvail['id'])->update([
                            'stage_start_date'    => $newStageStartdate,
                            'stage_continue_date' => $newStageContinuedate,
                            'stage_end_date'      => $newStageEnddate,
                        ]);

                    }
                }

                //update dates in all matches
                $matchesAll = TempFixture::where('tournament_id', $tournamentId)->get();
                if ($matchesAll->count() > 0) {
                    foreach ($matchesAll as $match) {
                        if ($match['match_datetime'] != null && $match['match_endtime'] != null) {
                            if ($oldSdate->lt($newdata['start_date'])) {
                                $newMatchStartdate = Carbon::parse($match['match_datetime'])->addDays($dateDiff);
                                $newMatchEnddate   = Carbon::parse($match['match_endtime'])->addDays($dateDiff);
                            }
                            if ($oldSdate->gt($newdata['start_date'])) {
                                $newMatchStartdate = Carbon::parse($match['match_datetime'])->subDays($dateDiff);
                                $newMatchEnddate   = Carbon::parse($match['match_endtime'])->subDays($dateDiff);
                            }
                            TempFixture::where('id', $match['id'])->update([
                                'match_datetime' => $newMatchStartdate,
                                'match_endtime'  => $newMatchEnddate,
                            ]);
                        }
                    }
                }
            }
            \Log::info("Pitch capacity date check --- end");

            $tournamentData = Tournament::where('id', $tournamentId)->update($newdata);

        } else {
            $newdata['slug']    = $this->generateSlug($data['name'] . Carbon::createFromFormat('d/m/Y', $newdata['start_date'])->year);
            $newdata['status']  = 'Unpublished';
            $newdata['user_id'] = $data['user_id'];
            $tournamentId       = Tournament::create($newdata)->id;
        }
        // Also Update the image Logo
        unset($newdata);
        // Now here we save the eurosport contact details
        $tournamentContactData                  = array();
        $tournamentContactData['first_name']    = $data['tournament_contact_first_name'];
        $tournamentContactData['last_name']     = $data['tournament_contact_last_name'];
        $tournamentContactData['telephone']     = $data['tournament_contact_home_phone'];
        $tournamentContactData['tournament_id'] = $tournamentId;

        // Save Tournament Contact Data
        if (isset($data['tournamentId']) && $data['tournamentId'] != 0) {

            $tournamentResult = TournamentContact::where('tournament_id', $tournamentId)->get();
            if ($tournamentResult->count() == 0) {
                TournamentContact::create($tournamentContactData);
            } else {
                $updatedData = TournamentContact::where('tournament_id', $tournamentId)->update($tournamentContactData);
            }
        } else {
            TournamentContact::create($tournamentContactData);
        }

        unset($tournamentContactData);
        // Save Tournament Venue Data
        // we have to loop for according to loations
        $locationCount = $data['locationCount'];
        $locData       = $data['locations'];
        $locationData  = array();
        if ($data['del_location'] && count($data['del_location']) > 0) {
            foreach ($data['del_location'] as $location) {
                $venue = Venue::find($location);
                if($venue) {
                    $venue->delete();
                }
            }
        }
        foreach ($locData as $location) {
            $locationData['id']            = $location['tournament_location_id'] ?? '';
            $locationData['name']          = $location['tournament_venue_name'] ?? '';
            $locationData['address1']      = $location['touranment_venue_address'] ?? '';
            $locationData['city']          = $location['tournament_venue_city'] ?? '';
            $locationData['postcode']      = $location['tournament_venue_postcode'] ?? '';
            $locationData['state']         = $location['tournament_venue_state'] ?? '';
            $locationData['country']       = $location['tournament_venue_country'] ?? '';
            $locationData['organiser']     = $location['tournament_venue_organiser'] ?? '';
            $locationData['tournament_id'] = $tournamentId;
            if (isset($locationData['id']) && $locationData['id'] != 0) {
                // Update Touranment Table Data
                Venue::where('id', $locationData['id'])->update($locationData);
            } else {
                //  TournamentContact::create($tournamentContactData);
                $locationId = Venue::create($locationData)->id;
            }
        }
        $tournamentData = array();
        $tournamentDays = $this->getTournamentDays($data['start_date'], $data['end_date']);

        $tournamentData = array(
            'id'                  => $tournamentId,
            'name'                => $data['name'],
            'tournamentStartDate' => $data['start_date'],
            'tournamentEndDate'   => $data['end_date'],

            'tournamentStatus'    => 'Unpublished',
            'tournamentLogo'      => ($data['image_logo'] != '') ? $this->tournamentLogo . $data['image_logo'] : '',
            'tournamentDays'      => ($tournamentDays) ? $tournamentDays : '2',
            'facebook'            => $data['facebook'],
            'twitter'             => $data['twitter'],
            'website'             => $data['website'],
            'maximum_teams'       => $data['maximum_teams'],
        );

        return $tournamentData;
    }
    private function getTournamentDays($startDate, $endDate)
    {
        $startDate = str_replace('/', '-', $startDate);
        $endDate   = str_replace('/', '-', $endDate);

        $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
        // TODO add one day
        $days = $days + 1;
        return $days;

        /*  $from=date_create(date('Y-m-d',strtotime($startDate)));
    print_r($from);exit;
    $to=date_create($endDate);
    $diff=date_diff($to,$from);
    print_r($diff);
    echo $diff->format('%R%a days');

    $your_date = strtotime($startDate);

    echo 'hello1';
    print_r( $date1);
    //print_r($your_date);
    exit;
    $created = Carbon::createFromFormat('d/m/Y', $startDate);
    $now = Carbon::createFromFormat('d/m/Y', $endDate);

    $days = ($created->diff($now)->days < 1) ? 1 : $created->diffForHumans($now)+1;
    return $days;
     */
    }
    public function edit($data)
    {
        return Tournament::where('id', $data['id'])->update($data);
    }

    public function delete($id)
    {
        return Tournament::find($id)->delete();
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
        //print_r($userData->toArray());
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

    public function addTournamentDetails($tournamentDetailData)
    {
        $token                     = JWTAuth::getToken();
        $authUser                  = JWTAuth::parseToken()->toUser();
        $userId                    = $authUser->id;
        $tournament                = new Tournament();
        $tournament->name          = $tournamentDetailData['tournament_name'];
        $tournament->slug          = $this->generateSlug($tournamentDetailData['tournament_name'] . Carbon::createFromFormat('d/m/Y', $tournamentDetailData['tournament_start_date'])->year);
        $tournament->maximum_teams = $tournamentDetailData['tournament_max_teams'];
        $tournament->user_id       = $userId;
        $tournament->start_date    = $tournamentDetailData['tournament_start_date'];
        $tournament->end_date      = $tournamentDetailData['tournament_end_date'];
        $tournament->status        = 'Unpublished';
        $tournament->save();
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

        // $ageCategoryDetail->competition->insertBefore(['id' => 'all', 'actual_name' => 'All groups']);
        // dd($ageCategoryDetail);

        $pitches = Pitch::where('tournament_id', $data['tournamentId'])->where('size', $ageCategoryDetail->pitch_size)->get();

        return ['ageCategoryDetail' => $ageCategoryDetail, 'pitches' => $pitches];
    }

    public function getAllPitchesWithDays($pitchId)
    {
        $pitchAvailability = PitchAvailable::where('pitch_id', $pitchId)->select('stage_no', 'stage_start_date', 'stage_start_time', 'stage_end_time')->get()->toArray();

        return $pitchAvailability;
    }

    public function scheduleAutomaticPitchPlanning($data)
    {
        // $scheduleMatchesCount = TempFixture::where('competition_id', $data['competition'])->where('is_scheduled', 1)->count();
        $scheduleMatchesCount = new TempFixture;
        $scheduleMatchesCount = isset($data['competition']) ? $scheduleMatchesCount->where('competition_id', $data['competition']) : $scheduleMatchesCount->where('age_group_id', $data['age_category']);
        $scheduleMatchesCount = $scheduleMatchesCount->where('is_scheduled', 1)->count();
        if ($scheduleMatchesCount > 0) {
            return ['status' => 'error', 'message' => 'You cannot schedule matches automatically as some of the matches are already scheduled.'];
        }

        $ageCategory = TournamentCompetationTemplates::where('id', $data['age_category'])->first();
        $teamIntervalCheck = TempFixture::where('age_group_id', $data['age_category'])->where('is_scheduled', 1)->get()->only(['id', 'match_datetime', 'match_end_time', 'home_team', 'away_team', 'home_team_placeholder_name', 'away_team_placeholder_name'])->toArray();

        $minimumTeamIntervalTime = $ageCategory->minimum_team_interval;
        $maximumTeamIntervalTime = $ageCategory->maximum_team_interval;

        // for normal match
        // $unscheduledMatchesForNormalMatch = TempFixture::where('competition_id', $data['competition'])->where('is_final_round_match', 0)->where('is_scheduled', 0)->get();
        $unscheduledMatchesForNormalMatch = new TempFixture;
        $unscheduledMatchesForNormalMatch = isset($data['competition']) ? $unscheduledMatchesForNormalMatch::where('competition_id', $data['competition']) : $unscheduledMatchesForNormalMatch::where('age_group_id', $data['age_category']);
        $unscheduledMatchesForNormalMatch = $unscheduledMatchesForNormalMatch->where('is_final_round_match', 0)->where('is_scheduled', 0)->get();
        // dd($unscheduledMatchesForNormalMatch);
        $normalMatchTotalTime  = ($ageCategory->game_duration_RR * $ageCategory->halves_RR) + $ageCategory->halftime_break_RR + $ageCategory->match_interval_RR;
        $requiredNormalMatchTotalTime = $normalMatchTotalTime * count($unscheduledMatchesForNormalMatch);

        // for final match
        // $unscheduledMatchesForFinalMatch = TempFixture::where('competition_id', $data['competition'])->where('is_final_round_match', 1)->where('is_scheduled', 0)->get();
        $unscheduledMatchesForFinalMatch = new TempFixture;
        $unscheduledMatchesForFinalMatch = isset($data['competition']) ? $unscheduledMatchesForFinalMatch::where('competition_id', $data['competition']) : $unscheduledMatchesForFinalMatch::where('age_group_id', $data['age_category']);
        $unscheduledMatchesForFinalMatch = $unscheduledMatchesForFinalMatch->where('is_final_round_match', 1)->where('is_scheduled', 0)->get();
        $finalMatchTotalTime = ($ageCategory->game_duration_FM * $ageCategory->halves_FM) + $ageCategory->halftime_break_FM + $ageCategory->match_interval_FM;
        $requiredFinalMatchTotalTime = $finalMatchTotalTime * count($unscheduledMatchesForFinalMatch);

        $totalMatchesToBeScheduled = count($unscheduledMatchesForNormalMatch) + count($unscheduledMatchesForFinalMatch);

        $totalRequiredTime = $requiredNormalMatchTotalTime + $requiredFinalMatchTotalTime;

        $totalPitchesAvailableTime = 0;

        $allPitches = Pitch::whereIn('id', $data['pitches'])->get()->keyBy('id');

        foreach ($data['pitches'] as $key => $pitchId) {
            $tempFixture = TempFixture::where('tournament_id', $data['tournamentId'])
                ->where('pitch_id', $pitchId)
                ->where('is_scheduled', 1)
                ->select(\DB::raw("SUM(time_to_sec(timediff(match_endtime, match_datetime)) / 60) as result"))
                ->first();

            $pitch = $allPitches[$pitchId];
            $availableTime = $pitch->pitch_capacity - $tempFixture->result;
            $totalPitchesAvailableTime = $totalPitchesAvailableTime + $availableTime;
        }
        if ($totalRequiredTime > $totalPitchesAvailableTime) {
            return ['status' => 'error', 'message' => 'Required time should be less than pitch available time.'];
        }

        $reservedStartTimeArray = [];
        $reservedEndTimeArray = [];
        $availableStartTimeArray = [];
        $availableEndTimeArray = [];
        $newReservedTimeArray = [];
        $pitchOpeningTimes = [];
        $pitchAvailableTime = [];

        foreach ($data['pitches'] as $key => $pitchId) {
            $pitchAvailability = PitchAvailable::where('pitch_id', $pitchId)->get();
            /*$tempFixtures = TempFixture::where('competition_id', $data['competition'])
               ->where('age_group_id', $data['age_category'])
               ->where('pitch_id', $pitchId)
               ->where('is_scheduled', 1)
               ->get();*/
            $tempFixtures = new TempFixture;
            if (isset($data['competition'])) {
                $tempFixtures = $tempFixtures->where('competition_id', $data['competition']);
            }
            $tempFixtures = $tempFixtures->where('age_group_id', $data['age_category'])->where('pitch_id', $pitchId)->where('is_scheduled', 1)->get();
// dd($tempFixtures);
            foreach ($pitchAvailability as $key => $pitchAvailable) {
                if(!isset($data['timings'][$pitchId]['days'][$key])) {
                    continue;
                }

                $pitchAvailableDate = Carbon::createFromFormat('d/m/Y', $pitchAvailable->stage_start_date)->format('Y-m-d');

                $pitchAvailableStart = Carbon::createFromFormat('d/m/Y H:i', $pitchAvailable->stage_start_date . ' ' . $pitchAvailable->stage_start_time);
                $pitchAvailableEnd = Carbon::createFromFormat('d/m/Y H:i', $pitchAvailable->stage_start_date . ' ' . $pitchAvailable->stage_end_time);

                $pitchStartDateTime = $pitchAvailable->stage_start_date . ' ' . $data['timings'][$pitchId]['time'][$key]['start_time'] . ':00';
                $pitchEndDateTime   = $pitchAvailable->stage_start_date . ' ' . $data['timings'][$pitchId]['time'][$key]['end_time'] . ':00';

                $pitchStartDateTime = Carbon::createFromFormat('d/m/Y H:i:s', $pitchStartDateTime);
                $pitchEndDateTime = Carbon::createFromFormat('d/m/Y H:i:s', $pitchEndDateTime);

                while ($pitchStartDateTime <= $pitchEndDateTime) {
                    $pitchAvailableTime[$pitchId][$pitchStartDateTime->timestamp] = 1;
                    if ($pitchStartDateTime < $pitchAvailableStart || $pitchStartDateTime > $pitchAvailableEnd) {
                        $pitchAvailableTime[$pitchId][$pitchStartDateTime->timestamp] = 0;
                    }
                    $pitchStartDateTime->addMinute(1);
                }

                $allPitchBreaks = PitchBreaks::where('availability_id', $pitchAvailable->id)->get();

                $availableStartTimeArray[] = $pitchAvailableStart->toDateTimeString();
                $i = 0;
                $pitchOpeningTimes[$i]['pitchStart'] = $pitchAvailableStart->toDateTimeString();
                if (count($allPitchBreaks) > 0) {
                    foreach ($allPitchBreaks as $key => $break) {
                        $availableEndTimeArray[]   = $pitchAvailableStart->format('Y-m-d') . ' ' . $break->break_start . '' . ':00';
                        $availableStartTimeArray[] = $pitchAvailableStart->format('Y-m-d') . ' ' . $break->break_end . '' . ':00';

                        $pitchOpeningTimes[$i]['pitchClose'] = $pitchAvailableStart->format('Y-m-d') . ' ' . $break->break_start . '' . ':00';
                        $i++;
                        $pitchOpeningTimes[$i]['pitchStart'] = $pitchAvailableStart->format('Y-m-d') . ' ' . $break->break_end . '' . ':00';

                        $availability = PitchAvailable::where('id', $break->availability_id)->first();

                        $stageStartTime = Carbon::createFromFormat('d/m/Y H:i', $availability->stage_start_date . ' ' . $break->break_start);
                        $stageEndTime = Carbon::createFromFormat('d/m/Y H:i', $availability->stage_start_date . ' ' . $break->break_end);

                        while ($stageStartTime < $stageEndTime) {
                            $pitchAvailableTime[$pitchId][$stageStartTime->timestamp] = 0;
                            $stageStartTime->addMinute(1);
                        }
                    }
                }

                $pitchUnAvailability = PitchUnavailable::where('pitch_id', $pitchId)->where(\DB::raw('DATE_FORMAT(match_start_datetime, "%Y-%m-%d")'), $pitchAvailableDate)->get();

                foreach ($pitchUnAvailability as $key => $value) {
                    $pitchUnavailableStart = Carbon::parse($value->match_start_datetime);
                    $pitchUnavailableEnd   = Carbon::parse($value->match_end_datetime);

                    while ($pitchUnavailableStart < $pitchUnavailableEnd) {
                        $pitchUnavailableStart->addMinute(1);
                        $pitchAvailableTime[$pitchId][$pitchUnavailableStart->timestamp] = 0;
                    }
                }

                $fixtures = TempFixture::where('pitch_id', $pitchId)->where('is_scheduled', 1)->where(\DB::raw('DATE_FORMAT(match_datetime, "%Y-%m-%d")'), $pitchAvailableDate)->get();

                foreach ($fixtures as $fixture) {
                    $matchStartDateTime = Carbon::parse($fixture->match_datetime);
                    $matchEndDateTime = Carbon::parse($fixture->match_endtime)->subMinute(1);

                    while ($matchStartDateTime < $matchEndDateTime) {
                        $matchStartDateTime->addMinute(1);
                        $pitchAvailableTime[$pitchId][$matchStartDateTime->timestamp] = 0;
                    }
                }
            }
        }

        $unscheduledMatches = TempFixture::where('tournament_id', $data['tournamentId']);
            // ->where('competition_id', $data['competition'])
            // ->where('is_scheduled', 0);
            // ->get();
            if (isset($data['competition'])) {
                $unscheduledMatches = $unscheduledMatches->where('competition_id', $data['competition']);
            }

            $unscheduledMatches = $unscheduledMatches->where('is_scheduled', 0)->get();
            // dd($unscheduledMatches);
        $matchScheduleArray = [];
        foreach ($unscheduledMatches as $match) {
            if ($match->is_final_round_match == 1) {
                $matchTime = $finalMatchTotalTime;
            } else {
                $matchTime = $normalMatchTotalTime;
            }

            foreach ($pitchAvailableTime as $pitchId => $availability) {
                $i = 0;
                $startTimeStamp = null;
                $isMatchScheduledFlag = false;
                foreach ($availability as $timestamp => $value) {
                    if ($matchTime == $i) {
                        $startTimeStamp = Carbon::createFromTimestamp($startTimeStamp);
                        $endTimeStamp = Carbon::createFromTimestamp($timestamp);

                        $matchScheduleArray[$match->id] = array(
                            'match_start_time' => clone ($startTimeStamp),
                            'match_end_time' => clone ($endTimeStamp),
                            'pitch_id' => $pitchId,
                            'venue_id' => $allPitches[$pitchId]->venue_id,
                        );

                        $teamIntervalCheck[] = array(
                            'id' => $match->id,
                            'match_datetime' => clone($startTimeStamp),
                            'match_endtime' => clone($endTimeStamp),
                            'home_team' => $match->home_team,
                            'away_team' => $match->away_team,
                            'home_team_placeholder_name' => $match->home_team_placeholder_name,
                            'away_team_placeholder_name' => $match->away_team_placeholder_name,
                        );

                        while ($startTimeStamp < $endTimeStamp) {
                            $pitchAvailableTime[$pitchId][$startTimeStamp->timestamp] = 0;
                            $startTimeStamp->addMinute(1);
                        }
                        $isMatchScheduledFlag = true;
                    }

                    if ($i < $matchTime && $value == 1) {
                        $canMatchBeSchedule = true;
                        if ($startTimeStamp == null) {
                            if(!isset($pitchAvailableTime[$pitchId][Carbon::createFromTimestamp($timestamp)->addMinute($matchTime)->timestamp])) {
                                continue;
                            }

                            $matchStartTimeStamp = Carbon::createFromTimestamp($timestamp);
                            $matchEndTimeStamp = (clone ($matchStartTimeStamp))->addMinute($matchTime);
                            $homeMaximumTeamIntervalTimeCheck = false;
                            $awayMaximumTeamIntervalTimeCheck = false;
                            $isFirstMatchOfHomeTeam = true;
                            $isFirstMatchOfAwayTeam = true;

                            foreach($teamIntervalCheck as $matchId => $matchDetails) {
                                $homeTeamCondition = false;
                                $awayTeamCondition = false;

                                if($match->home_team!=0 && $match->away_team!=0) {
                                    $homeTeamCondition = ($match->home_team == $matchDetails['home_team'] || $match->home_team == $matchDetails['away_team']);

                                    $awayTeamCondition = ($match->away_team == $matchDetails['home_team'] || $match->away_team == $matchDetails['away_team']);
                                } else {
                                    $homeTeamCondition = ($match->home_team_placeholder_name == $matchDetails['home_team_placeholder_name'] || $match->home_team_placeholder_name == $matchDetails['away_team_placeholder_name']);

                                    $awayTeamCondition = ($match->away_team_placeholder_name == $matchDetails['home_team_placeholder_name'] || $match->away_team_placeholder_name == $matchDetails['away_team_placeholder_name']);
                                }

                                if($homeTeamCondition) {
                                    $isFirstMatchOfHomeTeam = false;
                                }
                                if($awayTeamCondition) {
                                    $isFirstMatchOfAwayTeam = false;
                                }

                                if($homeTeamCondition || $awayTeamCondition) {
                                    $minimumBeforeMatchStartTimeStamp = (clone ($matchStartTimeStamp))->subMinute($minimumTeamIntervalTime);
                                    $minimumAfterMatchEndTimeStamp = (clone ($matchEndTimeStamp))->addMinute($minimumTeamIntervalTime);
                                    if((clone($matchDetails['match_datetime']))->between($minimumBeforeMatchStartTimeStamp, $minimumAfterMatchEndTimeStamp, false) || (clone($matchDetails['match_endtime']))->between($minimumBeforeMatchStartTimeStamp, $minimumAfterMatchEndTimeStamp, false)) {
                                        $canMatchBeSchedule = false;
                                    }

                                    $maximumBeforeMatchStartTimeStamp = (clone ($matchStartTimeStamp))->subMinute($maximumTeamIntervalTime);
                                    $maximumAfterMatchEndTimeStamp = (clone ($matchEndTimeStamp))->addMinute($maximumTeamIntervalTime);
                                    if(((clone($matchDetails['match_datetime']))->between($maximumBeforeMatchStartTimeStamp, $maximumAfterMatchEndTimeStamp)) || ((clone($matchDetails['match_endtime']))->between($maximumBeforeMatchStartTimeStamp, $maximumAfterMatchEndTimeStamp))) {
                                        if($homeTeamCondition) {
                                            $homeMaximumTeamIntervalTimeCheck = true;
                                        }
                                        if($awayTeamCondition) {
                                            $awayMaximumTeamIntervalTimeCheck = true;
                                        }
                                    }
                                }
                            }

                            if((!$homeMaximumTeamIntervalTimeCheck && !$isFirstMatchOfHomeTeam) || (!$awayMaximumTeamIntervalTimeCheck && !$isFirstMatchOfAwayTeam)) {
                                $canMatchBeSchedule = false;
                            }

                            if($canMatchBeSchedule == true) {
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
                if ($isMatchScheduledFlag == true) {
                    break;
                }
            }

            if(count($pitchAvailableTime) > 1) {
                // Rotate array
                $pitchAvailableTimeKeys = array_keys($pitchAvailableTime);
                $pitchAvailableTimeValues = $pitchAvailableTime[$pitchAvailableTimeKeys[0]];
                unset($pitchAvailableTime[$pitchAvailableTimeKeys[0]]);
                $pitchAvailableTime[$pitchAvailableTimeKeys[0]] = $pitchAvailableTimeValues;
            }
        }

        if($totalMatchesToBeScheduled != count($matchScheduleArray)) {
            return ['status' => 'error', 'message' => 'There is some error. All matches can not be schedule.'];
        }

        foreach ($matchScheduleArray as $matchId => $matchDetail) {
            $matchFixture = TempFixture::find($matchId);
            $matchFixture->match_datetime = $matchDetail['match_start_time'];
            $matchFixture->match_endtime = $matchDetail['match_end_time'];
            $matchFixture->pitch_id = $matchDetail['pitch_id'];
            $matchFixture->venue_id = $matchDetail['venue_id'];
            $matchFixture->is_scheduled = 1;
            $matchFixture->save();
        }

        return ['status' => 'Success', 'message' => 'Matches has been scheduled.'];
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

    public function duplicateTournament($data)
    {
        $existingTournament = Tournament::findOrFail($data['copy_tournament_id']);
        $existingTournamentAgeCategories = TournamentCompetationTemplates::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentCompetitions = Competition::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentFixtures = TempFixture::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentMatchStandings = MatchStanding::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentVenues = Venue::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentPitches = Pitch::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentAvailablePitches = PitchAvailable::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentUnAvailablePitches = PitchUnavailable::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentTeams = Team::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentTeamsManualRankings = TeamManualRanking::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentContacts = TournamentContact::where('tournament_id', $data['copy_tournament_id'])->get();
        $existingTournamentReferees = Referee::where('tournament_id', $data['copy_tournament_id'])->get();
        $tournamentClubs = TournamentClub::where('tournament_id', $data['copy_tournament_id'])->get();
        $tournamentUsers = TournamentUser::where('tournament_id', $data['copy_tournament_id'])->get();

        $allPositions = [];
        $teamsMappingArray = [];
        $venuesMappingArray = [];
        $pitchesMappingArray = [];
        $refereesMappingArray = [];
        $competitionsMappingArray = [];
        $ageCategoriesMappingArray = [];

        // saving tournament
        $newCopiedTournament = $existingTournament->replicate();
        $newCopiedTournament->name = $data['tournament_name'];
        $newCopiedTournament->slug = $this->generateSlug($data['tournament_name'] . Carbon::createFromFormat('d/m/Y', $existingTournament->start_date)->year);
        $newCopiedTournament->duplicated_from = $existingTournament->id;
        $newCopiedTournament->status = 'Unpublished';
        $newCopiedTournament->is_published_preview_once = 0;
        $newCopiedTournament->save();

        // saving tournament age categories        
        if($existingTournamentAgeCategories) {
            foreach ($existingTournamentAgeCategories as $ageCategory) {
                $copiedAgeCategory = $ageCategory->replicate();
                $copiedAgeCategory->tournament_id = $newCopiedTournament->id;
                $copiedAgeCategory->save();
                $ageCategoriesMappingArray[$ageCategory->id] = $copiedAgeCategory->id;

                // $positions = Position::where('age_category_id', $ageCategory->id)->get();

                // echo "<pre>";print_r($positions);echo "</pre>";exit;
                // $allPositions = array_merge($allPositions, $positions);
            }
        }

        // saving tournament competitions
        if($existingTournamentCompetitions) {
            foreach ($existingTournamentCompetitions as $competition) {
                if(isset($ageCategoriesMappingArray[$competition->tournament_competation_template_id])) {
                    $copiedCompetition = $competition->replicate();
                    $copiedCompetition->tournament_competation_template_id = $ageCategoriesMappingArray[$competition->tournament_competation_template_id];
                    $copiedCompetition->tournament_id = $newCopiedTournament->id;
                    $copiedCompetition->save();
                    $competitionsMappingArray[$competition->id] = $copiedCompetition->id;
                }
            }
        }

        // saving tournament venues
        if($existingTournamentVenues) {
            foreach ($existingTournamentVenues as $venue) {
                $copiedVenue = $venue->replicate();
                $copiedVenue->tournament_id = $newCopiedTournament->id;
                $copiedVenue->save();
                $venuesMappingArray[$venue->id] = $copiedVenue->id;
            }
        }

        // saving tournament associated pitches
        if($existingTournamentPitches) {
            foreach ($existingTournamentPitches as $pitch) {
                $copiedPitch = $pitch->replicate();
                $copiedPitch->tournament_id = $newCopiedTournament->id;
                $copiedPitch->venue_id = isset($venuesMappingArray[$pitch->venue_id]) ? $venuesMappingArray[$pitch->venue_id] : null;
                $copiedPitch->save();
                $pitchesMappingArray[$pitch->id] = $copiedPitch->id;
            }
        }

        // saving tournament pitch availability
        if($existingTournamentAvailablePitches) {
            foreach ($existingTournamentAvailablePitches as $availablePitch) {
                if(isset($pitchesMappingArray[$availablePitch->pitch_id])) {
                    $copiedAvailablePitch = $availablePitch->replicate();
                    $copiedAvailablePitch->tournament_id = $newCopiedTournament->id;
                    $copiedAvailablePitch->pitch_id = $pitchesMappingArray[$availablePitch->pitch_id];
                    $copiedAvailablePitch->save();
                }

                if(isset($pitchesMappingArray[$availablePitch->pitch_id])) {
                    $pitchBreak = PitchBreaks::where('pitch_id', $availablePitch->pitch_id)->first();
                    if($pitchBreak) {
                        $copiedPitchBreak = $pitchBreak->replicate();
                        $copiedPitchBreak->pitch_id = $pitchesMappingArray[$availablePitch->pitch_id];
                        $copiedPitchBreak->availability_id = $copiedAvailablePitch->id;
                        $copiedPitchBreak->save();
                    }
                }
            }
        }

        // saving tournament pitch unavailability
        if($existingTournamentUnAvailablePitches) {
            foreach ($existingTournamentUnAvailablePitches as $unAvailablePitch) {
                if(isset($pitchesMappingArray[$unAvailablePitch->pitch_id])) {
                    $copiedUnAvailablePitch = $unAvailablePitch->replicate();
                    $copiedUnAvailablePitch->tournament_id = $newCopiedTournament->id;
                    $copiedUnAvailablePitch->pitch_id = $pitchesMappingArray[$unAvailablePitch->pitch_id];
                    $copiedUnAvailablePitch->save();
                }
            }
        }

        // saving tournament referees
        $refereeNewAgeCategoriesArray = [];
        if($existingTournamentReferees) {
            foreach ($existingTournamentReferees as $referee) {
                if($referee->age_group_id != null) {
                    $explodedExistingRefereeAgeCategories = explode(",", $referee->age_group_id);
                    foreach ($explodedExistingRefereeAgeCategories as $key => $ageCategory) {
                        if(isset($ageCategoriesMappingArray[$ageCategory])) {
                            $refereeNewAgeCategoriesArray[] = $ageCategoriesMappingArray[$ageCategory];
                        }
                    }
                }

                $copiedTournamentReferee = $referee->replicate();
                $copiedTournamentReferee->tournament_id = $newCopiedTournament->id;
                $copiedTournamentReferee->age_group_id = ($referee->age_group_id != null) ? implode(",", $refereeNewAgeCategoriesArray) : null;
                $copiedTournamentReferee->save();
                
                $refereesMappingArray[$referee->id] = $copiedTournamentReferee->id;
            }
        }

        // saving tournament teams
        if($existingTournamentTeams) {
            foreach ($existingTournamentTeams as $team) {
                $copiedTeam = $team->replicate();
                $copiedTeam->tournament_id = $newCopiedTournament->id;
                $copiedTeam->competation_id = isset($competitionsMappingArray[$team->competation_id]) ? $competitionsMappingArray[$team->competation_id] : null;
                $copiedTeam->age_group_id = isset($ageCategoriesMappingArray[$team->age_group_id]) ? $ageCategoriesMappingArray[$team->age_group_id] : null;
                $copiedTeam->save();
                $teamsMappingArray[$team->id] = $copiedTeam->id;
            }
        }

        // saving positions
        if($ageCategoriesMappingArray) {
            foreach ($ageCategoriesMappingArray as $key => $ageCategory) {
                $positions = Position::where('age_category_id', $key)->get();
                foreach ($positions as $poskey => $position) {
                    if(isset($ageCategoriesMappingArray[$position->age_category_id])) {
                        $copiedPositions = $position->replicate();
                        $copiedPositions->age_category_id = $ageCategoriesMappingArray[$position->age_category_id];
                        $copiedPositions->team_id = isset($teamsMappingArray[$position->team_id]) ? $teamsMappingArray[$position->team_id] : null;
                        $copiedPositions->save();
                    }
                }
            }
        }

        // saving tournament fixtures
        if($existingTournamentFixtures) {
            foreach ($existingTournamentFixtures as $fixture) {
                if(isset($competitionsMappingArray[$fixture->competition_id])) {
                    $copiedFixture = $fixture->replicate();
                    $copiedFixture->tournament_id = $newCopiedTournament->id;
                    $copiedFixture->competition_id = $competitionsMappingArray[$fixture->competition_id];
                    $copiedFixture->venue_id = isset($venuesMappingArray[$fixture->venue_id]) ? $venuesMappingArray[$fixture->venue_id] : null;
                    $copiedFixture->age_group_id = isset($ageCategoriesMappingArray[$fixture->age_group_id]) ? $ageCategoriesMappingArray[$fixture->age_group_id] : null;
                    $copiedFixture->referee_id = isset($refereesMappingArray[$fixture->referee_id]) ? $refereesMappingArray[$fixture->referee_id] : null;
                    $copiedFixture->pitch_id = isset($pitchesMappingArray[$fixture->pitch_id]) ? $pitchesMappingArray[$fixture->pitch_id] : null;
                    $copiedFixture->match_winner = isset($teamsMappingArray[$fixture->match_winner]) ? $teamsMappingArray[$fixture->match_winner] : null;
                    $copiedFixture->home_team = isset($teamsMappingArray[$fixture->home_team]) ? $teamsMappingArray[$fixture->home_team] : 0;
                    $copiedFixture->away_team = isset($teamsMappingArray[$fixture->away_team]) ? $teamsMappingArray[$fixture->away_team] : 0;
                    $copiedFixture->save();
                }
            }
        }

        // saving tournament team manual rankings
        if($existingTournamentTeamsManualRankings) {
            foreach ($existingTournamentTeamsManualRankings as $teamManualRanking) {
                $copiedTeamManualRanking = $teamManualRanking->replicate();
                $copiedTeamManualRanking->tournament_id = $newCopiedTournament->id;
                $copiedTeamManualRanking->competation_id = isset($competitionsMappingArray[$teamManualRanking->competation_id]) ? $competitionsMappingArray[$teamManualRanking->competation_id] : null;
                $copiedTeamManualRanking->team_id = isset($teamsMappingArray[$teamManualRanking->team_id]) ? $teamsMappingArray[$teamManualRanking->team_id] : null;
                $copiedTeamManualRanking->save();
            }
        }


        // saving tournament contacts
        if($existingTournamentContacts) {
            foreach ($existingTournamentContacts as $tournamentContact) {
                $copiedTournamentContact = $tournamentContact->replicate();
                $copiedTournamentContact->tournament_id = $newCopiedTournament->id;
                $copiedTournamentContact->save();
            }
        }

        // saving tournament match standings
        if($existingTournamentMatchStandings) {
            foreach ($existingTournamentMatchStandings as $matchStanding) {
                $copiedTournamentMatchStanding = $matchStanding->replicate();
                $copiedTournamentMatchStanding->tournament_id = $newCopiedTournament->id;
                $copiedTournamentMatchStanding->competition_id = isset($competitionsMappingArray[$matchStanding->competition_id]) ? $competitionsMappingArray[$matchStanding->competition_id] : null;
                $copiedTournamentMatchStanding->team_id = isset($teamsMappingArray[$matchStanding->team_id]) ? $teamsMappingArray[$matchStanding->team_id] : null;
                $copiedTournamentMatchStanding->save();
            }
        }

        // saving tournament club
        if($tournamentClubs) {
            foreach ($tournamentClubs as $tournamentClub) {
                $copiedTournamentClub = $tournamentClub->replicate();
                $copiedTournamentClub->tournament_id = $newCopiedTournament->id;
                $copiedTournamentClub->save();
            }
        }

        if($tournamentUsers) {
            foreach ($tournamentUsers as $tournamentUser) {
                $copiedTournamentUser = $tournamentUser->replicate();
                $copiedTournamentUser->tournament_id = $newCopiedTournament->id;
                $copiedTournamentUser->save();
            }
        }


        return $newCopiedTournament;
    }

    public function duplicateTournamentList($data)
    {
        $authUser = JWTAuth::parseToken()->toUser();
        if(isset($data['tournamentNameSearch']) && $data['tournamentNameSearch'] !== '') {
            $tournamentName =  Tournament::where('tournaments.name', 'like', "%" . $data['tournamentNameSearch'] . "%");
            return $tournamentName->orderBy('name', 'asc')->get();
        } else {
            if($authUser->roles()->first()->slug == 'tournament.administrator') {
                return $authUser->tournaments()->orderBy('name', 'asc')->get();
            } 
            return Tournament::orderBy('name', 'asc')->get();
        }
    }
}
