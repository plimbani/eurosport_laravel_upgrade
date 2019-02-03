<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use Carbon\Carbon;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laraspace\Api\Repositories\TournamentRepository;
use Laraspace\Api\Repositories\Commercialisation\TransactionRepository;
use Laraspace\Http\Requests\Tournament\TournamentSummary;
use Laraspace\Http\Requests\Commercialisation\Tournament\TournamentByCustomerRequest;
use Laraspace\Models\User;

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
//            $response = $this->tournamentRepoObj->tournamentSummary($data['tournamentId']);
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
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Manage tournament and update details
     * @param Request $request
     */
    public function manageTournament(Request $request)
    {
        try {
            $requestData = $request->all();
            if (!empty($requestData['paymentResponse'])) {
                //Update payment details
                $response = $this->transactionRepoObj->updateTransaction($requestData);
            }
            if (!empty($requestData['tournament'])) {
                $requestData['tournament'] = [
                    'name' => $requestData['tournament']['tournament_name'],
                    'start_date' => Carbon::createFromFormat('d/m/Y', $requestData['tournament']['tournament_start_date']),
                    'end_date' => Carbon::createFromFormat('d/m/Y', $requestData['tournament']['tournament_end_date']),
                    'maximum_teams' => $requestData['tournament']['tournament_max_teams'],
                ];
                $response = $this->tournamentRepoObj->edit($requestData['tournament']);
            }
            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $response,
                        'error' => [],
                        'message' => 'Tournament details updated successfully.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => 'Something went wrong.']);
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
}
