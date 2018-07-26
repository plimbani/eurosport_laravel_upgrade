<?php

namespace Laraspace\Api\Controllers;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Mail;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    protected $viewClass = 'email.html.emailBody';
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    
        $this->middleware('guest');
    }
     /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */

    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }
        $password_reset = \DB::table('password_resets')->where('token', $token)->first();
       
        if(empty($password_reset)){
            throw new NotFoundHttpException;
        }
        $email = $password_reset->email;
         // dd($email);
        return view('auth.passwords.reset')->with('token', $token)->with('email', $email);
    }
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        dd($request->all());
        $email = $request->email;
       
        $this->validate($request, ['email' => 'required|email']);
        $user = User::select('first_name')->where('email',$email)->first();
        if($user)
             $user_data = array('email'=>$request->only('email'), 'first_name'=>$user->first_name);
        else
             return redirect()->back()->withErrors(['email_error' => true]); 
             



            $template = \App\Model\Templates::where('slug_name', 'reset_password')->get();
            if($template){

                $content = $template[0]->content;
                $subject = $template[0]->subject;
                $data['content']       =   $content;
                $data['first_name']    =   $user['first_name'];
                $data['email']         =   $request->email;

                 $data['content'] = str_replace('[PlayerName]', $data['first_name'], $data['content']);
                 $data['content'] = str_replace('[CLUBLOGO]', asset('admin_theme/layouts/layout/img/MCB_Icon.png'), $data['content']);
                 
                 $data_content = $data['content'];

                view()->composer('auth.emails.password', function($view) use ($data_content) {
                    $view->with([
                        'data_content' => $data_content
                    ]);
                });
             

                $response = Password::sendResetLink($user_data, function (Message $message) use ($subject){
                    $message->subject($subject);
                });
            }
      
    
        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('status', "An email has been sent to you with a password reset link")->with('email_sent',true)->with('email',$email);

            case Password::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ],[
            'confirmed' => 'Oops! Your passwords do not match'
        ]
        );

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });
        
        switch ($response) {
            case Password::PASSWORD_RESET:
                $email = $request->get('email');
                $user = User::select('first_name')->where('email',$email)->first()->toArray();
                $data = array('email'=>$email, 'first_name'=>$user['first_name']);
                $template_slug = 'password_reset_done';
                $template = \App\Model\Templates::where('slug_name', $template_slug)->get();
           
            if($template){

                $content = $template[0]->content;
                $subject = $template[0]->subject;
                $data['content']       =   $content;
                $data['first_name']    =   $user['first_name'];
                $data['email']         =   $request->email;
                // $data['token']    =   $request->token;
               
                
                                
                $data['content'] = str_replace('[PlayerName]', $data['first_name'], $data['content']);
                 $data['content'] = str_replace('[MCB Url]',  url('/'), $data['content']);
                 $data['content'] = str_replace('[CLUBLOGO]',  asset('admin_theme/layouts/layout/img/MCB_Icon.png'), $data['content']);

                
                // $mailData['subject'] = "Your password has been reset";
                $mailData['subject'] = $subject;
                $mailData['toMail'] =  $data['email'];

                
                $mailData['fromMail'] = getenv('FROM_EMAIL');
                 \Mail::send($this->viewClass, ['mail' => $data], function ($message) use ($mailData){
                        $message->to($mailData['toMail']);
                        $message->from($mailData['fromMail']);
                        $message->subject($mailData['subject']);
                     });       
                
            }
               
                // return redirect($this->redirectPath())->with('status', trans($response));

            default:
                    return redirect()->back()
                            ->withInput($request->only('email'))
                            ->withErrors(['email' => trans($response)]);
        }
    }
}
