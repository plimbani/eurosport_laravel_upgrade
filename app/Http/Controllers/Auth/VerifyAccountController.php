<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Notifications\VerifyEmail;

/**
 * Class ConfirmAccountController.
 */
class VerifyAccountController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * ConfirmAccountController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $token
     *
     * @return mixed
     */
    public function userActivation($token)
    {
        $user = User::where('token', $token)->first();
        if (! is_null($user)) {
            if ($user->is_verified === 1) {
                return redirect()->to('login')->with('success', 'user are already actived.');
            }
            $user->update(['is_verified' => 1]);

            return redirect()->to('login')->with('success', 'user active successfully.');
        }

        return redirect()->to('login')->with('warning', 'your token is invalid');
    }

    /**
     * @param $user
     *
     * @return mixed
     */
    public function sendConfirmationEmail(User $user)
    {
        $user->notify(new VerifyEmail($user->token));

        return redirect()->to('login')->with(
            'success',
            'A new confirmation e-mail has been sent to the address of the user.'
        );
    }
}
