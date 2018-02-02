<?php
namespace Laraspace\Api\Repositories;

use Laraspace\Models\Tournament;
use Laraspace\Models\Person;
use Laraspace\Models\Venue;
use Laraspace\Models\TournamentContact;
use Laraspace\Models\TournamentVenue;
use Laraspace\Models\TournamentTemplates;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Models\Pitch;
use Laraspace\Models\PitchAvailable;
use Laraspace\Models\TempFixture;
use Laraspace\Models\Team;
use Laraspace\Models\Referee;
use Laraspace\Models\UserFavourites;
use Laraspace\Models\Competition;
use Carbon\Carbon;
use JWTAuth;

class TournamentRepository
{
  public $slug;
    public function __construct()
    {
      $this->tournamentLogo =  getenv('S3_URL').'/assets/img/tournament_logo/';
    }
    public function getTournamentsByStatus($tournamentData)
    {
       $status = $tournamentData['status'];
       return Tournament::where('status',$status)->get();
    }
    public function getTournamentsBySlug($tournamentData)
    {
      $slug = $tournamentData;
      return Tournament::where('slug',$slug)->first();
    }
    public function getAll($status='', $user=null)
    {
      if($status == '') {
          $data = Tournament::
                  select('tournaments.*',
                 \DB::raw('IF(tournaments.logo is not null,CONCAT("'.$this->tournamentLogo.'", tournaments.logo),"" ) as tournamentLogo'));
      } else {
        $data = Tournament::where('status','=','Published')
                ->select('tournaments.*',
                \DB::raw('IF(tournaments.logo is not null,CONCAT("'.$this->tournamentLogo.'", tournaments.logo),"" ) as tournamentLogo'));

      }

      if($user) {
        $tournaments = $user->tournaments()->pluck('id');
        $data = $data->whereIn('id', $tournaments);
      }
      $data = $data->get();

      return $data;
      /*if($status == '') {
        return Tournament::get();
      }
      else{
        return Tournament::where('status','=','Published')->get();
      }*/
    }
    public function getAuthUserCreatedTournaments($status='')
    {
      if($status == '') {
          $data = Tournament::
                  select('tournaments.*',
                 \DB::raw('IF(tournaments.logo is not null,CONCAT("'.$this->tournamentLogo.'", tournaments.logo),"" ) as tournamentLogo'))
                    ->get();
      } else {
        $data = Tournament::where('status','=','Published')
                ->select('tournaments.*',
                \DB::raw('IF(tournaments.logo is not null,CONCAT("'.$this->tournamentLogo.'", tournaments.logo),"" ) as tournamentLogo')
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
    public function getTemplate($tournamentTemplateId) {
        $tournamentTemplateData = [];
        $tournamentTemplate = TournamentTemplates::find($tournamentTemplateId);
        $tournamentTemplateData['json_data'] = $tournamentTemplate->json_data;
        $tournamentTemplateData['image'] = $tournamentTemplate->image;
        return $tournamentTemplateData;
    }
    public function getAllTemplates($data=array())
    {
      if(is_array($data) && count($data['tournamentData'])>0){
        // TODO: need to Add
        return TournamentTemplates::get();
      } else {
        // here we modified the data
        return TournamentTemplates::get();
      }

    }
     public function getAllTemplatesFromMatches($data=array())
    {
      if(is_array($data) && count($data['tournamentData'])>0 && $data['tournamentData']['minimum_matches']!='' && $data['tournamentData']['total_teams']!=''){
        // TODO: need to Add
        return TournamentTemplates::where(['total_teams'=>$data['tournamentData']['total_teams'],'minimum_matches' => $data['tournamentData']['minimum_matches']])->orderBy('name')->get();
      } else {
        // here we modified the data
        return;
        // return TournamentTemplates::get();
      }

    }

    /**
     * Generate slug
     *
     */
    public function generateSlug($title, $extra)
    {
      $this->getUniqueSlug($title, $extra);
      return $this->slug;
    }
    /**
     * Get unique slug name
     *
     */
    public function getUniqueSlug($title, $extra)
    {
      $slug = str_slug($title.'-'.$extra);
      if(Tournament::where('slug',$slug)->exists())
      {
          $this->generateSlug($slug, $extra+1);
          return;
      }
      $this->slug=$slug;
    }

    public function create($data)
    {
        // Save Tournament Data
        $newdata = array();
        $newdata['name'] = $data['name'];
        $newdata['maximum_teams'] = $data['maximum_teams'];
        $newdata['start_date'] = $data['start_date'] ? $data['start_date'] : '';
        $newdata['end_date'] = $data['end_date'] ? $data['end_date'] : '';
        $newdata['website'] = $data['website'] ? $data['website'] : '';
        $newdata['facebook'] = $data['facebook'] ? $data['facebook'] : '';
        $newdata['twitter'] = $data['twitter'] ? $data['twitter'] : '';

        // For New One We set Status as Unpublished

        if($data['image_logo'] != ''){
            $newdata['logo'] = $data['image_logo'];
        } else {
            $newdata['logo'] = NULL;
        }
        // Now here we Save it For Tournament
        $imageChanged = true;
        if(isset($data['tournamentId']) && $data['tournamentId'] != 0){
           // Update Touranment Table Data
          $tournamentId = $data['tournamentId'];
          $newdata['start_date'] = Carbon::createFromFormat('d/m/Y', $newdata['start_date']);
          $newdata['end_date'] = Carbon::createFromFormat('d/m/Y', $newdata['end_date']);
          // here we check for image Logo is exist
          // means nothing need to updated it

          //update dates in pitch available
          $tournamentDetail = Tournament::where('id', $tournamentId)->first();
          $oldSdate = Carbon::createFromFormat('d/m/Y', $tournamentDetail->start_date);
          $dateDiff = $oldSdate->diffInDays($newdata['start_date']);
          if($dateDiff > 0) {
            $pitchCnt = Pitch::where('tournament_id',$tournamentId)->get()->count();
            $pitchAvailableAll = PitchAvailable::where('tournament_id',$tournamentId)->get();
            if($pitchCnt > 0 ) {
              foreach($pitchAvailableAll as $pitchAvail) {
                if ($oldSdate->lt($newdata['start_date'])) {
                  $newStageStartdate = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_start_date'])->addDays($dateDiff);
                  $newStageContinuedate = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_continue_date'])->addDays($dateDiff);
                  $newStageEnddate = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_end_date'])->addDays($dateDiff);
                }
                 if ($oldSdate->gt($newdata['start_date'])) {
                  $newStageStartdate = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_start_date'])->subDays($dateDiff);
                  $newStageContinuedate = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_continue_date'])->subDays($dateDiff);
                  $newStageEnddate = Carbon::createFromFormat('d/m/Y', $pitchAvail['stage_end_date'])->subDays($dateDiff);
                }
                PitchAvailable::where('id',$pitchAvail['id'])->update([
                    'stage_start_date' => $newStageStartdate,
                    'stage_continue_date' => $newStageContinuedate,
                    'stage_end_date' => $newStageEnddate,
                  ]);
                
              }
            }
          
            //update dates in all matches
            $matchesAll = TempFixture::where('tournament_id',$tournamentId)->get();
            if($matchesAll->count() > 0) {
              foreach($matchesAll as $match) {
                if( $match['match_datetime'] != null && $match['match_endtime'] != null ) {
                  if ($oldSdate->lt($newdata['start_date'])) {
                    $newMatchStartdate = Carbon::parse($match['match_datetime'])->addDays($dateDiff);
                    $newMatchEnddate = Carbon::parse($match['match_endtime'])->addDays($dateDiff);
                  }
                  if ($oldSdate->gt($newdata['start_date'])) {
                    $newMatchStartdate = Carbon::parse( $match['match_datetime'])->subDays($dateDiff);
                    $newMatchEnddate = Carbon::parse( $match['match_endtime'])->subDays($dateDiff);
                  }
                  TempFixture::where('id',$match['id'])->update([
                      'match_datetime' => $newMatchStartdate,
                      'match_endtime' => $newMatchEnddate,
                  ]);
                }
              }
            } 
          }
         


          $tournamentData = Tournament::where('id', $tournamentId)->update($newdata);

        } else {
         $newdata['slug'] = $this->generateSlug($data['name'].Carbon::createFromFormat('d/m/Y', $newdata['start_date'])->year,'');
         $newdata['status'] = 'Unpublished';
         $newdata['user_id'] = $data['user_id'];
         $tournamentId = Tournament::create($newdata)->id;
        }
        // Also Update the image Logo
        unset($newdata);
        // Now here we save the eurosport contact details
        $tournamentContactData =  array();
        $tournamentContactData['first_name'] = $data['tournament_contact_first_name'];
        $tournamentContactData['last_name'] = $data['tournament_contact_last_name'];
        $tournamentContactData['telephone'] = $data['tournament_contact_home_phone'];
        $tournamentContactData['tournament_id'] = $tournamentId;

        // Save Tournament Contact Data
        if(isset($data['tournamentId']) && $data['tournamentId'] != 0){

          $tournamentResult = TournamentContact::where('tournament_id', $tournamentId)->get();
            if($tournamentResult->count() == 0) {
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
        $locData = $data['locations'];
        $locationData = array();
        foreach($locData as $location) {
            $locationData['id'] =$location['tournament_location_id'] ?? '';
            $locationData['name'] =$location['tournament_venue_name'] ?? '';
            $locationData['address1'] =$location['touranment_venue_address'] ?? '';
            $locationData['city'] =$location['tournament_venue_city'] ?? '';
            $locationData['postcode'] =$location['tournament_venue_postcode'] ?? '';
            $locationData['state'] =$location['tournament_venue_state'] ?? '';
            $locationData['country'] =$location['tournament_venue_country'] ?? '';
            $locationData['organiser'] =$location['tournament_venue_organiser'] ?? '';
            $locationData['tournament_id']=$tournamentId;
            if(isset($locationData['id']) && $locationData['id'] != 0){
           // Update Touranment Table Data
             if(isset($data['del_location']) && $data['del_location'] != 0)
             {
                $data = Venue::find($data['del_location'])->delete();
             }
             Venue::where('id', $locationData['id'])->update($locationData);
            } else {
           //  TournamentContact::create($tournamentContactData);
             $locationId = Venue::create($locationData)->id;
            }
        }
        $tournamentData = array();
        $tournamentDays = $this->getTournamentDays($data['start_date'],$data['end_date']);

        $tournamentData = array(
          'id'=> $tournamentId,
          'name'=> $data['name'],
          'tournamentStartDate' => $data['start_date'],
          'tournamentEndDate' => $data['end_date'],

          'tournamentStatus'=> 'Unpublished',
          'tournamentLogo'=> ($data['image_logo'] != '') ? $this->tournamentLogo.$data['image_logo'] : '',
          'tournamentDays'=> ($tournamentDays) ? $tournamentDays : '2',
          'facebook' => $data['facebook'],
          'twitter' => $data['twitter'],
          'website' => $data['website'],
          'maximum_teams' => $data['maximum_teams'],
        );

        return $tournamentData;
    }
    private function getTournamentDays ($startDate,$endDate)
    {
      $startDate = str_replace('/', '-', $startDate);
      $endDate = str_replace('/', '-', $endDate);

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
    public function tournamentSummary($tournamentId) {
        $isMobileUsers = \Request::header('IsMobileUser');
        // here we put validation for tournament id is exist
        $summaryData = array();

        // we only consider relevent table data
       $locationData = Venue::where('tournament_id', $tournamentId)->get();

       // we get Multiple LocationIds
       // TODO:--
       $tempData=array();
       if(count($locationData) > 0) {
        foreach($locationData as $location) {
            $tempData['locationData'][]=$location;
        }

        $summaryData['locations']=$tempData['locationData'];

       }
       $tournamentCompetaionTemplateData = TournamentCompetationTemplates::where('tournament_id', $tournamentId)->get();


        $summaryData['tournament_teams'] = 0;
        $summaryData['tournament_matches'] = 0;

       if(count($tournamentCompetaionTemplateData) > 0 )
       {
	       foreach($tournamentCompetaionTemplateData as $tournamentData) {
	       	// here we consider whole string for total teams
	       	$tempData['total_teams'][] =  $tournamentData['disp_format_name'];
	       	$tempData['total_match'][] =  $tournamentData['total_match'];
	       	$tempData['category_age'][]=  $tournamentData['category_age'];
	       }
	      $summaryData['tournament_matches'] = array_sum($tempData['total_match']);
        $summaryData['tournament_teams'] = array_sum($tempData['total_teams']);

         $summaryData['tournament_groups']= implode(' , ',array_unique($tempData['category_age']));
     	}
       // TODO: Add Some Code For Mobile Data

       if( $isMobileUsers != '') {

        $tournamentPitches =  Pitch::with('pitchAvailability')->where('tournament_id',$tournamentId)->get();
        $stage_start_time = array();
        foreach($tournamentPitches as $tournamentPitch) {
          if($tournamentPitch->pitchAvailability) {
            foreach($tournamentPitch->pitchAvailability as $pitchAvailibility)
            {
              if($pitchAvailibility->stage_no == 1) {
                $stage_start_time[]= $pitchAvailibility->stage_start_time;
                break;
              }
            }
          }
        }
        // Get minimum Value and Set it
        $summaryData['tournament_start_time'] = min($stage_start_time);
        $summaryData['tournament_pitches'] = count($tournamentPitches);
        unset($stage_start_time);
         $peopleData = TournamentContact::with('tournaments')->where('tournament_id',$tournamentId)->get();
         if(count($peopleData) > 0) {
            $summaryData['tournament_contact'] = $peopleData[0];
         }

       } else {
         $tournamentPitch = Pitch::where('tournament_id', $tournamentId)->get();
         $summaryData['tournament_pitches'] = count($tournamentPitch);
         $peopleData = TournamentContact::where('tournament_id',$tournamentId)->get();
         if(count($peopleData) > 0) {
            $summaryData['tournament_contact'] = $peopleData[0];
         }
       }


       $summaryData['tournament_age_categories'] = count($tournamentCompetaionTemplateData);

         // TODO: Referee is Added
         $refereeCount = Referee::where(['tournament_id' => $tournamentId])->count();
         $summaryData['tournament_referees'] = $refereeCount;

        // TODO: country  is remaining depends on team
         $teamsCountries = Team::join('countries', function ($join) {
                            $join->on('teams.country_id', '=', 'countries.id');
                           })
                          ->where('teams.tournament_id',$tournamentId)
                          ->select('countries.name as country_name')
                          ->get();
          $summaryData['tournament_countries'] =  '';
          if(count($teamsCountries) > 0 )
          {
            foreach($teamsCountries as $teamCountry) {
              $tempData['tournament_countries'][]=  $teamCountry['country_name'];
          }

            $summaryData['tournament_countries'] = implode(' , ',array_unique($tempData['tournament_countries']));
          }

	       //$locationData = Venue::find();
       return $summaryData;
    }
    public function tournamentReport ($data) {
        $matchData  = TempFixture::where('temp_fixtures.tournament_id',$data['tournament_id'])
                ->where('venue_id',$data['location'])
              ->where('pitch_id',$data['pitch'])
                ->where('match_datetime','>=',Carbon::parse($data['start_date'])->format('m/d/Y'))
                ->where('match_datetime','<=',Carbon::parse($data['end_date'])->format('m/d/Y'))
                ->where('pitch_id',$data['pitch'])
                // ->Join('tournament_competation_template', 'tournament_competation_template.tournament_id', '=', 'tournament.id')
                ->get();

            return $matchData;


    }
    public function updateStatus($tournamentData)
    {
        $newdata = array();
        $newdata['status'] = $tournamentData['status'];
        $tournamentId =   $tournamentData['tournamentId'];
        return Tournament::where('id', $tournamentId)->update($newdata);
    }
    public function tournamentFilter($tournamentData)
    {
      // dd($tournamentData);
      $tournamentId = $tournamentData['tournamentData']['tournamentId'];
      $key = $tournamentData['tournamentData']['keyData'];
      $resultData = array();
      // now here we fetch data for specefic key
      if($tournamentData['tournamentData']['type'] == 'teams' || $tournamentData['tournamentData']['type'] == 'scheduleResult'){
        $reportQuery = Team::where('teams.tournament_id','=' ,$tournamentId);
        switch($key) {
          case 'team' :
            $resultData = $reportQuery->select('id','name as name')
                          ->get();
            break;
           case 'country' :
            $resultData = $reportQuery->join('countries','countries.id','=','teams.country_id')
                        ->select('countries.id as id','countries.name as name')
                        ->distinct('name')
                        ->get();
           break;
           case 'age_category' :
             $resultData = TournamentCompetationTemplates::where('tournament_id',$tournamentId)
                            ->select('id',\DB::raw("CONCAT(group_name, ' (', category_age,')') AS name"),'tournament_template_id')
                             ->get();
            break;
            case 'location':
            $resultData = Venue::where('tournament_id',$tournamentId)
                        ->select('id','name')
                        ->get();
                        //echo $resultData;
            break;
          case 'competation_group':
                 // $resultData = Competition::where('tournament_id',$tournamentId)
                 //                ->select('id','name')
                 //                ->get();
                  $resultData = TournamentCompetationTemplates::with('Competition')->where('tournament_id',$tournamentId)
                            ->select('id',\DB::raw("CONCAT(group_name, ' (', category_age,')') AS name"),'tournament_template_id')
                             ->get();
        }
      }else{

        $reportQuery = TempFixture::where('temp_fixtures.tournament_id','=' ,$tournamentId);
        switch($key) {
          case 'location' :
            $resultData = Venue::where('tournament_id',$tournamentId)
                        ->select('id','name')
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
          case 'age_category' :
            $resultData = $reportQuery->join('competitions','competitions.id','=','temp_fixtures.competition_id')
                        ->join('tournament_competation_template','competitions.tournament_competation_template_id','=','tournament_competation_template.id')
                        ->select('tournament_competation_template.id as id',\DB::raw("CONCAT(tournament_competation_template.group_name, ' (', tournament_competation_template.category_age,')') AS name"))
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
        return TournamentCompetationTemplates::where('tournament_id',$tournamentId)->select('id','group_name','category_age')->get();
    }
    public function getUserDefaultLoginTournament($data)
    {

      $userData = UserFavourites::where('users_favourite.user_id','=',$data['user_id'])
              ->where('users_favourite.is_default','=','1')
              ->leftJoin('tournaments','tournaments.id','=','users_favourite.tournament_id')
              ->select('tournaments.*','users_favourite.*',
                 \DB::raw('CONCAT("'.$this->tournamentLogo.'", tournaments.logo) AS tournamentLogo'))
              ->get()
              ->first();
      if(count($userData) > 0) {
        return $userData;
      }
    }
    private function getTournamentPitchStartTime($tournamentId) {
      // here we query the pitch_availibility table for getting the start time for tournament
     /* $pitches = PitchAvailable::whereIn('tournament_id',$tournamentId)
                ->select(\DB::raw('CONCAT(stage_start_date," ",stage_start_time) as TournamentStartTime'),'tournament_id as TId')
                ->orderBy('stage_start_time','asc')
                ->orderBy('stage_start_date','asc')
                ->get()->first(); */
      // TODO : Change the code to find first schedule match for that tournament

       $pitches = TempFixture::whereIn('tournament_id',$tournamentId)
                ->whereNotNull('match_datetime')
                ->select('match_datetime as TournamentStartTime',
                  'temp_fixtures.tournament_id as TId')
                ->orderBy('temp_fixtures.match_datetime','asc')
                ->get()->first();
      if($pitches) {
          return $pitches->toArray();
      } else {
        return '';
      }

    }
     public function getUserLoginFavouriteTournament($data)

    {

      //$url = getenv('S3_URL').'/assets/img/tournament_logo/';
      // Now here we attach the tournament Start Date Seperately for check the first started match
      $userData = UserFavourites::where('users_favourite.user_id','=',$data['user_id'])
              ->where('tournaments.status','=','Published')
              ->leftJoin('tournaments','tournaments.id','=','users_favourite.tournament_id')
              ->leftJoin('tournament_contact','tournaments.id','=','tournament_contact.tournament_id')
              ->select('tournaments.*',
                'users_favourite.*',
                'tournaments.id as TournamentId',
                'tournaments.start_date as TournamentStartTime',
                'tournament_contact.first_name',
                'tournament_contact.last_name',
                'tournament_contact.telephone',
                'tournament_contact.email',
                \DB::raw('CONCAT("'.$this->tournamentLogo.'", tournaments.logo) AS tournamentLogo'))
              ->get()->toArray();
      //print_r($userData->toArray());
      $tournament_ids = array();
      if(count($userData) > 0) {
        foreach($userData as $tournamentData) {
          $tournament_ids[] = $tournamentData['TournamentId'];
        }

        // now call function and send tournament ids
        $tournamentStartTimeArr = $this->getTournamentPitchStartTime($tournament_ids);

        foreach($userData as $index=>$userData1) {

          if($tournamentStartTimeArr) {
          foreach($tournamentStartTimeArr as $key=>$tournamentTime) {
            if($userData1['TournamentId'] == $tournamentStartTimeArr['TId']) {
                $userData[$index]['TournamentStartTime'] = date('Y-m-d H:i:s' ,strtotime($tournamentStartTimeArr['TournamentStartTime']));
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

      $url = getenv('S3_URL');
      $clubData = Team::where('teams.tournament_id','=',$data['tournament_id'])
                  ->whereNotNull('teams.group_name')
                  ->leftJoin('clubs','clubs.id','=','teams.club_id')
                  ->leftjoin('countries','countries.id','=','teams.country_id')
                  ->select('clubs.id as ClubId','clubs.name as clubName','countries.id as countryId','countries.name as CountryName',
                  \DB::raw('CONCAT("'.$url.'", countries.logo ) AS CountryLogo')
                    )
                  ->groupBy('clubs.id','countries.id')
                   ->get();
      return (count($clubData) > 0) ? $clubData : 0;
    }

    public function addTournamentDetails($tournamentDetailData)
    {
      $token=JWTAuth::getToken();
      $authUser = JWTAuth::parseToken()->toUser();
      $userId = $authUser->id;
      $tournament = new Tournament();
      $tournament->name = $tournamentDetailData['tournament_name'];
      $tournament->maximum_teams = $tournamentDetailData['tournament_max_teams'];
      $tournament->user_id = $userId;
      $tournament->start_date = $tournamentDetailData['tournament_start_date'];
      $tournament->end_date = $tournamentDetailData['tournament_end_date'];
      $tournament->status = 'Unpublished';
      $tournament->save();
    }

    public function getCategoryCompetitions($data)
    {
      $categoryCompetitions = Competition::where('tournament_competation_template_id', $data['ageGroupId']);
      if(isset($data['competationType'])) {
        $categoryCompetitions = $categoryCompetitions->where('competation_type', $data['competationType']);
      }
      if(isset($data['competationRoundNo'])) {
        $categoryCompetitions = $categoryCompetitions->where('competation_round_no', $data['competationRoundNo']);
      }
      $categoryCompetitions = $categoryCompetitions->get();
      return $categoryCompetitions;
    }

    public function saveCategoryCompetitionColor($competitionColorData)
    {
      foreach($competitionColorData as $key=>$data) {
        $competition = Competition::where('id', $key)->update(['color_code' => $data]);
      }
    }
}
