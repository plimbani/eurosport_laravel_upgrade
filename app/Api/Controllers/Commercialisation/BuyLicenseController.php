<?php

namespace Laraspace\Api\Controllers\Commercialisation;

// Need to Define Only Contracts
use JWTAuth;
use UrlSigner;
use Carbon\Carbon;
use Laraspace\Api\Services\Commercialisation\TransactionService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Laraspace\Http\Requests\Commercialisation\BuyLicense\GenerateHashKeyRequest;
use Laraspace\Http\Requests\Commercialisation\BuyLicense\SignedUrlForBuyLicensePrintRequest;
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
                            'message' => 'Your payment has been done successfully.'
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_UNPROCESSABLE_ENTITY, 'data' => [], 'error' => [], 'message' => $ex->getMessage()]);
        }
    }

    /**
     * @desc :API created for Haskey generate 
     */
    public function generateHashKey(GenerateHashKeyRequest $request)
    {
        $requestData = $request->all();
        $amount = ($requestData['tournamentPricingValue'] * 100);
        if ( is_float($amount) )
        {
            $amount = (int)($amount);
        }
        
        $orderId = 'ORDER-' . uniqid() . '-' . time();
        $shaInString = 'AMOUNT=' . ($amount) . config('app.SHA_IN_PASS_PHRASE') .
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
    public function generatePaymentReceipt(Request $request, $id)
    {  
        try {
            return $this->transactionObj->generatePaymentReceipt($request->all(),$id);
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

    public function paymentCallback(Request $request) {
        if($request['STATUS'] == 5 || $request['STATUS'] == 9)
		{
			//Authorized-success OR Payment_requested
			return redirect('payment?' . http_build_query($request->all()));
		}
		else
		{
			//Payment not success
			$paymentStatus = config('app.payment_status');
			if(isset($paymentStatus[$request['STATUS']]))
			{
				$statusMessage = $paymentStatus[$request['STATUS']];
				$statusMessage = str_replace('_', ' ', $statusMessage);
				$request['STATUS_MESSAGE'] = ucwords($statusMessage);
			}
			else
			{
				$request['STATUS_MESSAGE'] = "Failed";
			}
			return redirect('paymentfailure?' . http_build_query($request->all()));
		}
    }

    public function getSignedUrlForBuyLicensePrint(SignedUrlForBuyLicensePrintRequest $request)
    {
        $tournamentId = $request['tournamentData']['tournament_id'];
        $userName = $request['tournamentData']['user_name'];
        $tournamentRecord = $request['tournamentData']['tournament'];
        $data['user_name'] = $userName;
        $data = $this->dataForManageLicense($data,$tournamentRecord);
        ksort($data);
        $reportData  = http_build_query($data);

        $signedUrl = UrlSigner::sign(secure_url('api/license/receipt/generate/'. $tournamentId.'?'.$reportData),Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));


        return $signedUrl;
    }

    public function getPaymentStatusMessages()
    {
        $paymentMessages = Config('config-variables.payment_status_messages');
        return $paymentMessages;
    }

    public function dataForManageLicense($data,$tournamentRecord)
    {
        if (array_key_exists('id', $tournamentRecord) && $tournamentRecord['id'] != '')
        {
            $data['teamDifference'] = $tournamentRecord['teamDifference'];
            $data['id'] = $tournamentRecord['id'];
        }
        $data['tournament_max_teams'] = $tournamentRecord['tournament_max_teams'];
        $data['custom_tournament_format'] = $tournamentRecord['custom_tournament_format'];
        $data['tournament_type'] = $tournamentRecord['tournament_type'];


        $data['tournamentLicenseBasicPriceDisplay'] = number_format((float)$tournamentRecord['tournamentLicenseBasicPriceDisplay'], 2, '.', '');
        $data['tournamentLicenseAdvancePriceDisplay'] = number_format((float)$tournamentRecord['tournamentLicenseAdvancePriceDisplay'], 2, '.', '');

        $data['payment_currency'] = $tournamentRecord['payment_currency'];
        $data['gpbConvertValue'] = $tournamentRecord['gpbConvertValue'];

        if ( $tournamentRecord['payment_currency'] == 'GBP')
        {
           $data['transactionDifferenceAmountValue'] = number_format((float)$tournamentRecord['transactionDifferenceAmountValue']*($tournamentRecord['gpbConvertValue']), 2, '.', '');
        }
        else
        {
            $data['transactionDifferenceAmountValue'] = number_format((float)$tournamentRecord['transactionDifferenceAmountValue'], 2, '.', '');
        }

        return $data;
    }
}
