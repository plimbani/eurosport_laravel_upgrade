<?php

namespace Laraspace\Http\Controllers;

use Illuminate\Http\Request;
use Laraspace\Contracts\ApiContract;
use Laraspace\Models\Pitch;

class PitchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiContract $apiObj)
    {
        $this->apiObj = $apiObj;
        // $this->middleware('auth');
        // $this->middleware('jwt.auth');
    }

    public function index()
    {
        $pitches = $this->apiObj->api->get('pitches');

        return view('pitch.index')->with('pitches', $pitches);
    }

    public function create()
    {
        // $pitches = $this->apiObj->api->get('pitches');
        // $location = 'Barcelona';
        // $days = 4;
        // $timeSlot['30'] = ['9:00', '9.30', '10:00', '10:30', '11:00', '11.30', '12:00', '12:30', '13:00', '13:30', '14:00', '14.30', '15:00', '15:30', '16:00', '16.30', '17:00', '17:30', '18:00'];
        // $timeSlot['45'] = ['9.00', '9:45', '10.30', '11:15', '12:00', '12:45', '14.00', '14:45', '15:30', '16:15', '17:00', '17:45'];
        // $unavailable = [ '1-10:30', '1-13:30', '1-15:00', '2-12:30', '3-10:30', '3-15:30', '4-12:00', '4-16:00' ];
        // $locationId = 1;

        // // $match = $dispatcher->with($matchData)->post('match/create');
        // return view('pitch.create')->with(compact('location', 'days', 'timeSlot', 'unavailable', 'locationId'));
    }

    public function store(Request $request)
    {

        // unset($request->input('location'));
        $pitchNumber = '1234';
        $availability = '80';

        $pitchData = Pitch::create([
            'name' => $request->input('name'),
            'pitch_number' => $pitch_number,
            'type' => $request->input('type'),
            'location_id' => '1',
            'time_slot' => $request->input('time_slot'),
            'availability' => $availability,
            ]);

        // return ['status' => true, 'pitch' => $pitch];
        $pitch = $dispatcher->with($pitchData)->post('pitch/create');
    }
}
