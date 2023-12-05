<?php

namespace App\Api\Controllers;

use App\Api\Contracts\RefereeContract;
use App\Api\Repositories\RefereeRepository;
use App\Http\Requests\Referee\DeleteRequest;
use App\Http\Requests\Referee\GetRefereesRequest;
use App\Http\Requests\Referee\GetSignedUrlForRefereeSampleDownloadRequest;
use App\Http\Requests\Referee\RefereeDetailRequest;
use App\Http\Requests\Referee\StoreRequest;
use App\Http\Requests\Referee\UpdateRequest;
// Need to Define Only Contracts
use Carbon\Carbon;
use Illuminate\Http\Request;
use UrlSigner;

/**
 * Referees Resource Description.
 *
 * @Resource("Referees")
 *
 * @Author Kparikh@aecordigital.com
 */
class RefereeController extends BaseController
{
    public function __construct(RefereeContract $refereeObj, RefereeRepository $refereeRepoObj)
    {
        $this->refereeObj = $refereeObj;
        $this->refereeRepoObj = $refereeRepoObj;
        // $this->middleware('jwt.auth');
        $this->data = [];
    }

    /**
     * Show all Referee Details.
     *
     * Get a JSON representation of all the Referee.
     *
     * @Get("/referee")
     *
     * @Versions({"v1"})
     *
     * @Response(200, body={"id": 10, "username": "foo"})
     */
    public function getReferees(GetRefereesRequest $request)
    {
        return $this->refereeObj->getAllReferees($request->all()['tournamentData']);
    }

    /**
     * Create  referee.
     *
     * Create New Referee
     *
     * @Post("/referee/create")
     *
     * @Versions({"v1"})
     *
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function createReferee(StoreRequest $request)
    {
        return $this->refereeObj->createReferee($request);
    }

    public function updateReferee(UpdateRequest $request)
    {
        $data = $request->all()['data'];
        // dd($data);

        return $this->refereeObj->edit($data, $data['refereeId']);
    }

    public function refereeDetail(RefereeDetailRequest $request)
    {
        return $this->refereeRepoObj->getRefereeFromId($request->refereeId);
        // dd($request->refereeId);
        // return $this->refereeObj->refereeDetail($request);

    }

    /**
     * Edit  referee.
     *
     * @Post("/referee/edit/{$id}")
     *
     * @Versions({"v1"})
     *
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request, $refereeId)
    {
        return $this->refereeObj->edit($request, $refereeId);
    }

    public function deleteReferee(DeleteRequest $request, $deleteId)
    {
        return $this->refereeObj->deleteReferee($deleteId);
    }

    /**
     * Upload referees
     */
    public function uploadRefereesExcel(Request $request)
    {
        return $this->refereeObj->uploadRefereesExcel($request);
    }

    /**
     * Get signed url for referee sample download
     */
    public function getSignedUrlForRefereeSampleDownload(GetSignedUrlForRefereeSampleDownloadRequest $request)
    {
        $signedUrl = UrlSigner::sign(secure_url('api/referee/downloadSampleUploadSheet'), Carbon::now()->addMinutes(config('config-variables.signed_url_interval')));

        return $signedUrl;
    }

    /**
     * Download sample upload sheet
     */
    public function downloadSampleUploadSheet(Request $request)
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename='RefereesUploadSpreadsheet.xls'",
        ];

        return response()->download(base_path('resources/sample_uploads/RefereesUploadSpreadsheet.xls'), 'RefereesUploadSpreadsheet.xls', $headers);
    }
}
