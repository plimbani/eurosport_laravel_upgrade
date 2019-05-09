<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Http\Response;
use Laraspace\Models\Country;

/**
 * Registration Description.
 *
 * @Resource("register")
 *
 * @Author supasani@aecordigital.com
 */
class CountryController extends BaseController
{

    public function getList()
    {
        try {
            $list = Country::orderBy('name', 'asc')->get()->pluck('id', 'name');
            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $list,
                        'error' => [],
                        'message' => 'Country list has been get successfully.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => $ex->getCode(), 'data' => [], 'error' => [], 'message' => 'Something wen\'t wrong. Please try again later.']);
        }
    }

}
