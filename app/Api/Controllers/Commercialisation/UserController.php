<?php

namespace Laraspace\Api\Controllers\Commercialisation;

use Illuminate\Http\Response;
use Laraspace\Http\Requests\Commercialisation\Customer\UpdateRequest as UpdateCusRequest;
use Laraspace\Api\Repositories\UserRepository;
use Laraspace\Models\User;
use JWTAuth;

/**
 * Tournament Resource Description.
 *
 * @Resource("tournament")
 *
 */
class UserController extends BaseController
{

    public function __construct(UserRepository $userRepObj)
    {
        $this->userRepoObj = $userRepObj;
        $this->userImagePath = getenv('S3_URL') . '/assets/img/users/';
    }

    /**
     * Get user details     
     * @param int $userId user id
     * @return json
     */
    public function getDetails()
    {
        try {
            $authUser = JWTAuth::parseToken()->toUser();            
            $user = $this->userRepoObj->getUserById($authUser->id);
            return response()->json([
                        'success' => true,
                        'status' => Response::HTTP_OK,
                        'data' => $user,
                        'error' => [],
                        'message' => 'Get details of user successfully.'
            ]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_NOT_FOUND, 'data' => [], 'error' => [],
                        'message' => 'Somethind went wrong. Please try again letter.']);
        }
    }

    /**
     * Update customer details
     * @param storeRegRequest $request
     * @return json
     */
    public function updateUser(UpdateCusRequest $request)
    {
        try {
            $authUser = JWTAuth::parseToken()->toUser();
            $data = $request->all();
            $status = $this->userRepoObj->updateUser($data, $authUser->id);
            unset($data);
            if ($status) {
                return response()->json(['success' => true, 'status' => Response::HTTP_OK,
                            'data' => [], 'error' => [],
                            'message' => 'User details have been updated successfully.'
                ]);
            } else {
                return response()->json(['success' => false, 'status' => Response::HTTP_FORBIDDEN,
                            'data' => [], 'error' => [],
                            'message' => 'This email address already exists.'
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_NOT_FOUND, 'data' => [], 'error' => [],
                        'message' => 'Somethind went wrong. Please try again letter.']);
        }
    }

    /**
     * Update customer by super admin
     * @param UpdateCusRequest $request
     * @return array
     */
    public function updateUserByAdmin(UpdateCusRequest $request)
    {
        try {
            $data = $request->all();
            $status = $this->userRepoObj->updateUser($data, $data['customer_id']);
            unset($data);
            if ($status) {
                return response()->json(['success' => true, 'status' => Response::HTTP_OK,
                            'data' => [], 'error' => [],
                            'message' => 'User details has been updated successfully.'
                ]);
            } else {
                return response()->json(['success' => false, 'status' => Response::HTTP_FORBIDDEN,
                            'data' => [], 'error' => [],
                            'message' => 'This email address already exists.'
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'status' => Response::HTTP_NOT_FOUND, 'data' => [], 'error' => [],
                        'message' => $ex->getMessage()]);
        }
    }
}
