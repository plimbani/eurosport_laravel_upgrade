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

class TournamentRepository
{
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
        $newdata['start_date'] = $data['start_date'];
        $newdata['end_date'] = $data['end_date'];
        $newdata['website'] = $data['website'];
        $newdata['facebook'] = $data['facebook'];
        $newdata['twitter'] = $data['twitter'];
        $newdata['logo'] = $data['image_logo'];

        // For New One We set Status as Unpublished
        $newdata['status'] = 'UnPublished';
        $newdata['user_id'] = 1;

        // Now here we Save it For Tournament
        $tournamentId = Tournament::create($newdata)->id;   
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
        TournamentContact::create($tournamentContactData);
        unset($tournamentContactData);
        // Save Tournament Venue Data     
        // we have to loop for according to loations
        $locationCount = $data['locationCount'];
        $locationData = $data['locations'];
        foreach($locationData as $location) {
            $locationData['name'] =$location['tournament_venue_name'] ?? ''; 
            $locationData['address1'] =$location['touranment_venue_address'] ?? '';
            $locationData['city'] =$location['tournament_venue_city'] ?? '';
            $locationData['postcode'] =$location['tournament_venue_postcode'] ?? '';
            $locationData['state'] =$location['tournament_venue_state'] ?? '';
            $locationData['country'] =$location['tournament_venue_country'] ?? '';
            $locationData['tournament_id']=$tournamentId;
            // $locationData['organiser'] =$data['tournament_venue_organiser'];
            $locationId = Venue::create($locationData)->id;   

        }
        
        //TournamentVenue::create(array('tournament_id'=>$tournamentId,'venue_id'=>$locationId));
        
        return $tournamentId;        
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
            $tempData['locationData'][]=$location['name'].'('.$location['country'].')';
        }
        $summaryData['locations']=implode(',',$tempData['locationData']);
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
            
            $summaryData['tournament_contact'] = $peopleData[0]['first_name'].','.$peopleData[0]['last_name'];
         }
        
	       //$locationData = Venue::find();
       return $summaryData;
    }
}
