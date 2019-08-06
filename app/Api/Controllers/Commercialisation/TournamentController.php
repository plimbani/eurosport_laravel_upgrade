<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use Carbon\Carbon;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laraspace\Models\Tournament;
use Laraspace\Models\TempFixture;
use Laraspace\Models\TournamentCompetationTemplates;
use Laraspace\Api\Repositories\TournamentRepository;
use Laraspace\Api\Repositories\Commercialisation\TransactionRepository;
use Laraspace\Http\Requests\Tournament\TournamentSummary;
use Laraspace\Http\Requests\Commercialisation\Tournament\TournamentByCustomerRequest;
use Laraspace\Models\User;
use Laraspace\Models\PitchAvailable;
use Laraspace\Http\Requests\Commercialisation\Tournament\ManageTournamentRequest;

/**
 * Tournament Resource Description.
 *
 * @Resource("tournament")
 *
 */
class TournamentController extends BaseController
{

    /**
     * @param object $tournamentObj
     */
    public function __construct(TournamentRepository $tournamentObj)
    {
        $this->tournamentRepoObj = $tournamentObj;
        $this->transactionRepoObj = new TransactionRepository();
    }

    /**
     * Get list of tournaments for logged in user
     * @return array
     */
    public function getList()
    {
        try {
            $authUser = JWTAuth::parseToken()->toUser();
            $user = User::find($authUser->id);
            $data = $this->tournamentRepoObj->getAll('', $user);

            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $data,
                        'error' => [],
                        'message' => 'Tournament list.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Get tournament details
     * @param TournamentSummary $request
     * @return string
     */
    public function getTournament(TournamentSummary $request)
    {
        try {
            $data = $request->all();
            $response = $this->tournamentRepoObj->getTournamentDetails($data['tournamentId']);
            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $response,
                        'error' => [],
                        'message' => 'Tournament list.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Get Tournament by access code method
     * @param Request $request
     * @return array
     */
    public function getTournamentByCode(Request $request)
    {
        try {
            $data = $request->all();
            $response = $this->tournamentRepoObj->getTournamentByAccessCode($data['tournament']);
            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $response,
                        'error' => [],
                        'message' => 'Tournament list.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => $ex->getMessage()]);
        }
    }

    /**
     * Manage tournament and update details
     * @param Request $request
     */
    public function manageTournament(ManageTournamentRequest $request)
    {
        try {
            $requestData = $request->all();
            if (!empty($requestData['paymentResponse'])) {
                //Update payment details
                $this->transactionRepoObj->updateTransaction($requestData);
            }
            $tournament = Tournament::findOrFail($requestData['tournament']['old_tournament_id']);
            $requestTournamentStartDate = Carbon::createFromFormat('d/m/Y',$requestData['tournament']['tournament_start_date']);

            $requestTournamentEndDate = Carbon::createFromFormat('d/m/Y',$requestData['tournament']['tournament_end_date']);

            $requestTournamentStartDateFormat = Carbon::parse($requestTournamentStartDate)->format('Y-m-d');
            $requestTournamentEndDateFormat = Carbon::parse($requestTournamentEndDate)->format('Y-m-d');

            $tournamentMaximumTeam = $requestData['tournament']['tournament_max_teams'];
            
            $tournamentFixture = TempFixture::where('tournament_id', $requestData['tournament']['old_tournament_id'])->where(function ($query) use ($requestTournamentStartDateFormat,$requestTournamentEndDateFormat) {
                $query->orWhere('match_datetime', '<', $requestTournamentStartDateFormat)
                ->orwhere('match_datetime', '>', $requestTournamentEndDateFormat);
            })->count();

            $tournamentPitch = PitchAvailable::where('tournament_id', $requestData['tournament']['old_tournament_id'])->where(function ($query) use ($requestTournamentStartDateFormat,$requestTournamentEndDateFormat) {
                $query->orWhere('stage_start_date', '<', $requestTournamentStartDateFormat)
                ->orwhere('stage_start_date', '>', $requestTournamentEndDateFormat);
            })->count();  

            $tournamentCompetationTemplates = TournamentCompetationTemplates::where('tournament_id', $requestData['tournament']['old_tournament_id'])->pluck('total_teams')->sum();

            // Tournament update license 
            if (!empty($requestData['tournament'])) {        
                if($tournamentFixture == 0 && $tournamentPitch == 0 && $tournamentCompetationTemplates <= $tournamentMaximumTeam){

                    $customTournamentFormat = '';

                    if($requestData['tournament']['tournament_type'] == 'cup' && $requestData['tournament']['custom_tournament_format'] == 0) {
                        $customTournamentFormat = 0;
                    }else if($requestData['tournament']['tournament_type'] == 'cup' && $requestData['tournament']['custom_tournament_format'] == 1) {
                        $customTournamentFormat = 1;
                    } else {
                        $customTournamentFormat = NULL;   
                    }

                    $requestData['tournament'] = [
                        'id' => $requestData['tournament']['old_tournament_id'],
                        'name' => $requestData['tournament']['tournament_name'],
                        'start_date' => Carbon::createFromFormat('d/m/Y', $requestData['tournament']['tournament_start_date']),
                        'end_date' => Carbon::createFromFormat('d/m/Y', $requestData['tournament']['tournament_end_date']),
                        'maximum_teams' => $requestData['tournament']['tournament_max_teams'],
                        'tournament_type' => $requestData['tournament']['tournament_type'],
                        'custom_tournament_format' => $customTournamentFormat,
                    ];   
                } else {

                    if ( $tournamentFixture > 0)
                    {
                        if ( $tournament->maximum_teams != $requestData['tournament']['tournament_max_teams'])
                        {
                            $message = 'Please unschedule matches before editing the number of teams in your tournament.';
                        }
                        else
                        {
                            $message = 'Please unschedule matches before changing the tournament dates.';
                        } 
                    }
                    else if ($tournamentPitch > 0)
                    {
                        if ( $tournament->maximum_teams != $requestData['tournament']['tournament_max_teams'])
                        {
                            $message = 'Please unschedule pitches before editing the number of teams in your tournament.';
                        }
                        else
                        {
                            $message = 'Please unschedule pitches before changing the tournament dates.';
                        } 
                    }
                    else
                    {
                        $message = 'Please remove age categories before editing the number of teams in your tournament.';
                    }

                    return response()->json(['status' => 'error', 'message' => $message]);
                }
                $this->tournamentRepoObj->edit($requestData['tournament']);
            }
            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $requestData,
                        'error' => [],
                        'message' => 'Tournament details updated successfully.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => $ex->getMessage()]);
        }
    }

    /**
     * Get list of tournaments of customer for admin
     * @return array
     */
    public function getTournamentByCustomer(TournamentByCustomerRequest $request)
    {
        try {
            $requestData = $request->all();
            $user = User::find($requestData['customer_id']);
            $data = $this->tournamentRepoObj->getAll('', $user);

            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $data,
                        'error' => [],
                        'message' => 'Tournament list.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => 'Something went wrong.']);
        }
    }


    public function getUserTransactions() {
        try {
            $authUser = JWTAuth::parseToken()->toUser();
            $user = User::find($authUser->id);
            $response = $this->tournamentRepoObj->getUserTransactions($user);
            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $response,
                        'error' => [],
                        'message' => 'Tournament list.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => $ex->getMessage()]);
        }
    }
}
