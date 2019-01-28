<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laraspace\Api\Repositories\TournamentRepository;
use Laraspace\Http\Requests\Tournament\TournamentSummary;
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
    public function tournamentSummary(TournamentSummary $request)
    {
        try {
            $data = $request->all();
            $response = $this->tournamentRepoObj->tournamentSummary($data['tournamentId']);
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
            $data = $request->all();
            
        } catch (\Exception $ex) {
            
        }
    }
}
