<?php

namespace Laraspace\Api\Controllers\Commercialisation;

// Need to Define Only Contracts
use Laraspace\Api\Services\Commercialisation\TransactionService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Laraspace\Http\Requests\Commercialisation\BuyLicense\CustomerTransactionsRequest;

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
    
    public function __construct(TransactionService $transactionSerObj)
    {
        $this->transactionObj = $transactionSerObj;
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
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => $ex->getMessage()]);
        }
    }

    /**
     * @desc :API created for Haskey generate 
     */
    public function generateHashKey(Request $request)
    {
        $requestData = $request->all();
        
        $orderId = 'ORDER-' . uniqid() . '-' . time();
        $shaInString = 'AMOUNT=' . ($requestData['tournamentPricingValue'] * 100) . config('app.SHA_IN_PASS_PHRASE') .
                'CURRENCY=' . substr($requestData['currency_type'], 0, 3) . config('app.SHA_IN_PASS_PHRASE') . 'ORDERID=' . $orderId . config('app.SHA_IN_PASS_PHRASE') .
                'PMLIST=' . $requestData['PMLIST'] . config('app.SHA_IN_PASS_PHRASE') . 'PMLISTTYPE=' . $requestData['PMLISTTYPE'] . config('app.SHA_IN_PASS_PHRASE') .
                'PSPID=' . config('app.PSPID') . config('app.SHA_IN_PASS_PHRASE');

        $shaSign = hash(config('app.SHA_ALGO'), $shaInString);
        return response()->json([
                    'success' => true,
                    'status' => Response::HTTP_OK,
                    'data' => ['shaSignIn' => $shaSign, 'tournamentPricingValue' => ($requestData['tournamentPricingValue'] * 100), 'pspid' => config('app.PSPID'), 'orderId' => $orderId],
                    'error' => [],
                    'message' => 'Hash key genreated successfully.'
        ]);
    }

    /**
     * Generate PDF for payment receipt
     * @param Request $request
     */
    public function generatePaymentReceipt(Request $request)
    {
        try {
            return $this->transactionObj->generatePaymentReceipt($request->all());
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => $ex->getMessage()]);
        }
    }

    /**
     * Get list of customer's transaction
     */
    public function getCustomerTransactions(CustomerTransactionsRequest $request)
    {
        try {
            $reqData = $request->all();
            $data = $this->transactionObj->customerTransactions($reqData['tournament_id']);

            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $data,
                        'error' => [],
                        'message' => 'Transaction list get successfully.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => $ex->getMessage()]);
        }
    }
}
