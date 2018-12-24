<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use Illuminate\Http\Response;
use Laraspace\Http\Requests\Commercialisation\BuyLicense\AddRequest;
// Need to Define Only Contracts
use Laraspace\Api\Services\Commercialisation\BuyLicenseService;
use Laraspace\Api\Repositories\TournamentRepository;

/**
 * Buy License Description.
 */
class BuyLicenseController extends BaseController
{

    /**
     * Create a new controller instance.
     * @param object $buyLicenseObj
     * @return void
     */
    // public function __construct(RegisterContract $registerObj)
    public function __construct(BuyLicenseService $buyLicenseObj)
    {
        $this->buyLicenseObj = $buyLicenseObj;
        $this->tournamentObj = new TournamentRepository();
    }

    /**
     * Add tournament and buy license
     * @param StoreRequest $request
     */
    public function addTournament(AddRequest $request)
    {
        try {
            //Add tournament
            $tournamentRes = $this->tournamentObj->addTournamentDetails($request->all());
            if (FALSE !== $tournamentRes) {
                return response()->json([
                            'success' => true,
                            'status' => Response::HTTP_OK,
                            'data' => ['status' => $tournamentRes],
                            'error' => [],
                            'message' => 'Tournament has been added successfully.'
                ]);
            }
//            dd($request->all());
//            $response = $this->buyLicenseObj->buyLicense($request->all());
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_NOT_FOUND, 'data' => [], 'error' => [],
                        'message' => 'Somethind went wrong. Please try again letter.']);
        }
    }
}
