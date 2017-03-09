<?php

namespace Laraspace\Repositories;

use Laraspace\Models\Venue;

class VenueRepository
{
    public function getAll()
    {
        return Venue::get();
    }

    public function getVenue()
    {
        return Venue::select('name', 'id')->get();
    }

    public function create($data)
    {
        // Set Venue Details

        $data['name'] = $data['venues_name'];
        $data['contact_no'] = $data['venues_contact_no'];
        $data['address1'] = $data['venues_address1'];
        $data['address2'] = $data['venues_address2'];
        $data['address3'] = $data['venues_address3'];
        $data['email_address'] = $data['venues_email_address'];
        $data['city'] = $data['venues_city'];
        $data['postcode'] = $data['venues_postcode'];
        $data['state'] = $data['venues_state'];
        $data['country'] = $data['venues_country'];

        //Todo: Default Values
        $data['county'] = $data['venues_country'];

        return Venue::create($data);
    }

    public function delete($data)
    {
        return Venue::find($data['id'])->delete();
    }

    public function edit($data, $venueId)
    {
        return Venue::where('id', $venueId)->update($data);
    }

    public function getVenueFromId($venueId)
    {
        return Venue::find($venueId);
    }
}
