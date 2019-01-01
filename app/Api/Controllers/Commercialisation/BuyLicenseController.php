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
        $this->ingenicoShaPassPhrase = 'b709e0ae-ab5b-4a78-bfc7-0bd54612d622';
        $this->ingenicoShaAlgo = 'sha512';
        $this->ingenicoPspid = 'EasymatchmanagerQA';
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
                $shaInString = 'AMOUNT=' . $requestData['total_amount'] . $this->ingenicoShaPassPhrase . 'CURRENCY=EUR' . $this->ingenicoShaPassPhrase . 'ORDERID=' . $orderId . $this->ingenicoShaPassPhrase . 'PSPID=' . $this->ingenicoPspid . $this->ingenicoShaPassPhrase;
                $shaSign = hash('sha512', $shaInString);

                return response()->json([
                            'success' => true,
                            'status' => Response::HTTP_OK,
                            'payment_details' => ['shaSignIn' => $shaSign, 'pspid' => $this->ingenicoPspid, 'orderId' => $orderId],
                            'data' => $tournamentRes,
                            'error' => [],
                            'message' => 'Tournament has been added successfully.',
                ]);
            }
        } catch (\Exception $ex) {
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
                return redirect()->route('commerialisation.thankyou');
            }
        } catch (\Exception $ex) {
            
        }
    }

    /**
      @desc :static API created for haskey generate need to change once data come realtime
     */
    public function generateHashKey()
    {
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
