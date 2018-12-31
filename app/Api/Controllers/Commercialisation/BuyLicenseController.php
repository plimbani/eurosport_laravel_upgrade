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
    public function buyLicense(AddRequest $request)
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
    /**
    @desc :static API created for haskey generate need to change once data come realtime
    */
    public function generateHashKey(){
        $string = 'AMOUNT=2000b709e0ae-ab5b-4a78-bfc7-0bd54612d622CURRENCY=EURb709e0ae-ab5b-4a78-bfc7-0bd54612d622ORDERID=ORD22b709e0ae-ab5b-4a78-bfc7-0bd54612d622PSPID=EasymatchmanagerQAb709e0ae-ab5b-4a78-bfc7-0bd54612d622';
        $shaSign = hash('sha512', $string);

        return response()->json([
            'success' => true,
            'status' => Response::HTTP_OK,
            'data' => $shaSign,
            'error' => [],
            'message' => 'Hash Key Genreated successfully.'
        ]);
    }
}
