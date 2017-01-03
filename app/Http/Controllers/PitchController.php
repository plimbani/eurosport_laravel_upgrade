<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\PitchContract;
use App\Models\Pitch;
use App\Repositories\PitchRepository;

class PitchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PitchContract $pitchObj)
    {
        $this->pitchObj = $pitchObj;
        $this->middleware('auth');
        $this->timeSlot['30'] = ['9:00', '9.30', '10:00', '10:30', '11:00', '11.30', '12:00', '12:30', '13:00', '13:30', '14:00', '14.30', '15:00', '15:30', '16:00', '16.30', '17:00', '17:30', '18:00'];
        $this->timeSlot['45'] = ['9.00', '9:45', '10.30', '11:15', '12:00', '12:45', '14.00', '14:45', '15:30', '16:15', '17:00', '17:45'];
    }

    public function index()
    {
        $pitches = $this->apiObj->api->get('pitches');

        return view('pitch.index')->with('pitches', $pitches);
    }

    public function create()
    {
        // $pitches = $this->apiObj->api->get('matches');
        $location = 'Barcelona';
        $days = 4;
        $timeSlot['30'] = ['9:00', '9.30', '10:00', '10:30', '11:00', '11.30', '12:00', '12:30', '13:00', '13:30', '14:00', '14.30', '15:00', '15:30', '16:00', '16.30', '17:00', '17:30', '18:00'];
        $timeSlot['45'] = ['9.00', '9:45', '10.30', '11:15', '12:00', '12:45', '14.00', '14:45', '15:30', '16:15', '17:00', '17:45'];
        $unavailable = [ '1-10:30', '1-13:30', '1-15:00', '2-12:30', '3-10:30', '3-15:30', '4-12:00', '4-16:00' ];
        $locationId = 1;

        return view('pitch.create')->with(compact('location', 'days', 'timeSlot', 'unavailable', 'locationId'));
    }

    public function pitchAllocation($tSlot = '30')
    {
        $days = 4;
        if ($tSlot === '30') {
            $timeSlot = ['9:00', '9.30', '10:00', '10:30', '11:00', '11.30', '12:00', '12:30', '13:00', '13:30', '14:00', '14.30', '15:00', '15:30', '16:00', '16.30', '17:00', '17:30', '18:00'];
        } elseif ($tSlot === '45') {
            $timeSlot = ['9.00', '9:45', '10.30', '11:15', '12:00', '12:45', '14.00', '14:45', '15:30', '16:15', '17:00', '17:45'];
        }

        $unavailable = [ '1-10:30', '1-13:30', '1-15:00', '2-12:30', '3-10:30', '3-15:30', '4-12:00', '4-16:00' ];

        return ['days' => $days, 'timeSlot' => $timeSlot, 'unavailable' => $unavailable];
    }

    public function store(Request $request)
    {

        // unset($request->input('location'));
        $pitchId = 0;
        $pitch = $this->pitchObj->createPitch($request);
        if ($request->pitch_id !== 0 && isset($request->pitch_id)) {
            $pitch = $this->pitchObj->editPitch($request, $request->pitch_id);
        }

        if (isset($pitch['status_code']) && $pitch['status_code'] === 200) {
            $pitchId = $pitch['pitch_id'];
            $pitch = $this->pitchObj->getPitchById($pitchId);
        }
        $days = 4;
        $tslot = $request->input('time_slot');
        $timeSlot = ['9.00', '9:45', '10.30', '11:15', '12:00', '12:45', '14.00', '14:45', '15:30', '16:15', '17:00', '17:45'];
        if ($request->input('time_slot') === '30') {
            $timeSlot = ['9:00', '9.30', '10:00', '10:30', '11:00', '11.30', '12:00', '12:30', '13:00', '13:30', '14:00', '14.30', '15:00', '15:30', '16:00', '16.30', '17:00', '17:30', '18:00'];
        }
        $unavailable = [ '1-10:30', '1-13:30', '1-15:00', '2-12:30', '3-10:30', '3-15:30', '4-12:00', '4-16:00' ];

        return ['status' => true, 'pitch' => $pitch, 'pitch_id' => $pitchId, 'days' => $days, 'timeSlot' => $timeSlot, 'unavailable' => $unavailable];
    }
}
