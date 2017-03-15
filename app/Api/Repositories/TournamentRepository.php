<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\Tournament;
use Laraspace\Models\Person;
use Laraspace\Models\Venue;
use Laraspace\Models\TournamentContact;
use Laraspace\Models\TournamentVenue;
use Laraspace\Models\TournamentTemplates;
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
       // $newdata['logo'] = $data['logo'];

        // For New One We set Status as Unpublished
        $newdata['status'] = 'UnPublished';
        $newdata['user_id'] = 1;

        // Now here we Save it For Tournament
        $tournamentId = Tournament::create($newdata)->id;      
        unset($newdata);  
        // Now here we save the eurosport contact details
        $peopleData =  array();
        $peopleData['first_name'] = $data['tournament_contact_first_name'];
        $peopleData['last_name'] = $data['tournament_contact_last_name'];
        $peopleData['home_phone'] = $data['tournament_contact_home_phone'];
        $peopleId = Person::create($peopleData)->id;  
        // Save Tournament Contact Data
        TournamentContact::create(array('tournament_id'=>$tournamentId,'people_id'=>$peopleId));
        unset($peopleData);
        // Save Tournament Venue Data     
        $locationData['name'] =$data['tournament_venue_name']; 
        $locationData['address1'] =$data['touranment_venue_address1'];
        $locationData['city'] =$data['tournament_venue_city'];
        $locationData['postcode'] =$data['tournament_venue_postcode'];
        $locationData['state'] =$data['tournament_venue_state'];
        $locationData['country'] =$data['tournament_venue_country'];
        // $locationData['organiser'] =$data['tournament_venue_organiser'];
        $locationId = Venue::create($locationData)->id;
        TournamentVenue::create(array('tournament_id'=>$tournamentId,'venue_id'=>$locationId));
        
        return $tournamentId;        
    }

    public function edit($data)
    {
        return Tournament::where('id', $data['id'])->update($data);
    }

    public function delete($data)
    {
        return Tournament::find($data['id'])->delete();
    }
}
