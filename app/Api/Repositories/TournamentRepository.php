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
use Laraspace\Models\TempFixture;
use Carbon\Carbon;

class TournamentRepository
{
    public function getTournamentsByStatus($tournamentData)
    {
       $status = $tournamentData['status'];
       return Tournament::where('status',$status)->get();   
    }
    public function getAll()
    {
        return Tournament::get();
    }
    public function getTemplate($tournamentTemplateId) { 
        
        return TournamentTemplates::find($tournamentTemplateId)->json_data;
    }
    public function getAllTemplates()
    {
        return TournamentTemplates::get();
    }
    public function create($data)
    {
      
        // Save Tournament Data
        $newdata = array();
        $newdata['name'] = $data['name'];
        $newdata['start_date'] = $data['start_date'] ? $data['start_date'] : '';
        $newdata['end_date'] = $data['end_date'] ? $data['end_date'] : '';
        $newdata['website'] = $data['website'] ? $data['website'] : '';
        $newdata['facebook'] = $data['facebook'] ? $data['facebook'] : '';
        $newdata['twitter'] = $data['twitter'] ? $data['twitter'] : '';
        $newdata['logo'] = $data['image_logo'] ? $data['image_logo'] : '';

        // For New One We set Status as Unpublished
        $newdata['status'] = 'UnPublished';
        $newdata['user_id'] = 1;

        // Now here we Save it For Tournament
        if(isset($data['tournamentId']) && $data['tournamentId'] != 0){
           // Update Touranment Table Data 
          $tournamentId = $data['tournamentId'];
          // unset($newdata['start_date']);
          // unset($newdata['end_date']);
          $newdata['start_date'] = Carbon::createFromFormat('d/m/Y', $newdata['start_date']);
          $newdata['end_date'] = Carbon::createFromFormat('d/m/Y', $newdata['end_date']);
          $tournamentData = Tournament::where('id', $tournamentId)->update($newdata);
          
        } else {      
         $tournamentId = Tournament::create($newdata)->id;    
        } 
        //$tournamentId = Tournament::create($newdata)->id;   
        // Also Update the image Logo
        //Tournament::where('id',$tournamentId)->update('logo'=>'tournament_'.$tournamentId);   
        unset($newdata);  
        // Now here we save the eurosport contact details
        $tournamentContactData =  array();
        $tournamentContactData['first_name'] = $data['tournament_contact_first_name'];
        $tournamentContactData['last_name'] = $data['tournament_contact_last_name'];
        $tournamentContactData['telephone'] = $data['tournament_contact_home_phone'];
        $tournamentContactData['tournament_id'] = $tournamentId;
        
        // Save Tournament Contact Data
        if(isset($data['tournamentId']) && $data['tournamentId'] != 0){
           // Update Touranment Table Data 
          $updatedData = TournamentContact::where('tournament_id', $tournamentId)->update($tournamentContactData);
          
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
            $locationData['tournament_id']=$tournamentId;
            // $locationData['organiser'] =$data['tournament_venue_organiser'];
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

        $tournamentData = array('id'=> $tournamentId, 'name'=> $data['name'],'tournamentStartDate' => $data['start_date'], 'tournamentEndDate' => $data['end_date'], 
            'tournamentStatus'=> 'UnPublished',
            'tournamentLogo' => $data['image_logo'], 
            'tournamentDays'=> 2);

        return $tournamentData;        
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

         
       if(count($tournamentCompetaionTemplateData) > 0 )
       {	
	       foreach($tournamentCompetaionTemplateData as $tournamentData) {
	       	// here we consider whole string for total teams	
	       	$tempData['total_teams'][] =  $tournamentData['disp_format_name'];
	       	$tempData['total_match'][] =  $tournamentData['total_match'];  
	       	$tempData['age_group'][]=$tournamentData['group_name'];    	
	       }
	      $summaryData['tournament_matches'] = array_sum($tempData['total_match']);
        $summaryData['tournament_teams'] = array_sum($tempData['total_teams']);
         $summaryData['tournament_groups']= implode(',',$tempData['age_group']);
     	}

       $tournamentPitch = Pitch::where('tournament_id', $tournamentId)->get();

       $summaryData['tournament_age_categories'] = count($tournamentCompetaionTemplateData);
        
         $summaryData['tournament_pitches'] = count($tournamentPitch);
         // TODO: referee is remaining
         $summaryData['tournament_referees'] = '--';
        
        // TODO: country  is remaining depends on team
         $summaryData['tournament_countries'] = '--';

         $peopleData = TournamentContact::where('tournament_id',$tournamentId)->get();

         // means they have Data
         if(count($peopleData) > 0) {
            $summaryData['tournament_contact'] = $peopleData[0];
            //$summaryData['tournament_contact'] = $peopleData[0]['first_name'].','.$peopleData[0]['last_name'];
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
}
