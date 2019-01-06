<?php

namespace Laraspace\Api\Controllers\Commercialisation;

// Need to Define Only Contracts
use Laraspace\Api\Services\Commercialisation\TransactionService;
use Laraspace\Api\Repositories\TournamentRepository;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Laraspace\Http\Requests\Commercialisation\BuyLicense\AddRequest;

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
    public function __construct(TransactionService $transactionSerObj)
    {
        $this->transactionObj = $transactionSerObj;
        $this->tournamentObj = new TournamentRepository();
    }

    /**
     * Add tournament and buy license
     * @param StoreRequest $request
     */
    public function buyLicense(AddRequest $request)
    {
        try {            
            $requestData = $request->all();
            //Add tournament
            $tournamentRes = $this->tournamentObj->addTournamentDetails($requestData, 'api');
            if (FALSE !== $tournamentRes) {
                $orderId = 'ORDER-' . $tournamentRes->id . '-' . time();
                $shaInString = 'AMOUNT=' . $requestData['total_amount'] . config('app.SHA_IN_PASS_PHRASE') . 'CURRENCY=EUR' . config('app.SHA_IN_PASS_PHRASE') . 'ORDERID=' . $orderId . config('app.SHA_IN_PASS_PHRASE') . 'PSPID=' . config('app.PSPID') . config('app.SHA_IN_PASS_PHRASE');
                $shaSign = hash(config('app.SHA_ALGO'), $shaInString);

                return response()->json([
                            'success' => true,
                            'status' => Response::HTTP_OK,
                            'payment_details' => ['shaSignIn' => $shaSign, 'pspid' => config('app.PSPID'), 'orderId' => $orderId],
                            'data' => $tournamentRes,
                            'error' => [],
                            'message' => 'Tournament has been added successfully.',
                ]);
            }
        } catch (\Exception $ex) {
            dd($ex);
            return response()->json(['success' => false, 'status' => Response::HTTP_NOT_FOUND, 'data' => [], 'error' => [],
                        'message' => 'Somethind went wrong. Please try again letter.']);
        }
    }

    /**
     * Add payment response which is got from payment gateway
     * @param Request $request
     * @return void
     */
    public function paymentResponse(Request $request)
    {
        try {
            $transaction = $this->transactionObj->paymentResponse($request->all());
            if (FALSE !== $transaction) {
                return response()->json([
                            'success' => true,
                            'status' => Response::HTTP_OK,
                            'data' => $transaction,
                            'error' => [],
                            'message' => 'You payment has been done successfully.'
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => 'Something went wrong.']);
        }
    }

    /**
     * @desc :API created for Haskey generate 
     */
    public function generateHashKey(Request $request)
    {
        $requestData = $request->all();
        $orderId = 'ORDER-' . uniqid() . '-' . time();
        $shaInString = 'AMOUNT=' . ($requestData['total_amount'] * 100) . config('app.SHA_IN_PASS_PHRASE') . 'CURRENCY=EUR' . config('app.SHA_IN_PASS_PHRASE') . 'ORDERID=' . $orderId . config('app.SHA_IN_PASS_PHRASE') . 'PSPID=' . config('app.PSPID') . config('app.SHA_IN_PASS_PHRASE');
        $shaSign = hash(config('app.SHA_ALGO'), $shaInString);
        return response()->json([
                    'success' => true,
                    'status' => Response::HTTP_OK,
                    'data' => ['shaSignIn' => $shaSign, 'total_amount' => ($requestData['total_amount'] * 100), 'pspid' => config('app.PSPID'), 'orderId' => $orderId],
                    'error' => [],
                    'message' => 'Hash key genreated successfully.'
        ]);
    }
}
