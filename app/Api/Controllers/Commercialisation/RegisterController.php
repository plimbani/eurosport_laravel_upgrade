<?php
namespace Laraspace\Api\Controllers\Commercialisation;
use Illuminate\Http\Request;
use DB;
use Laraspace\Http\Controllers\Controller;
class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $request){
      $fname = $request->input('firstname');
      $lname = $request->input('lastname');
      $email = $request->input('email');
      $password = $request->input('password');
      $ins_people = array("first_name"=>$fname,"last_name"=>$lname,"primary_email"=>$email);
      DB::table('people')->insert($ins_people);
      $id = DB::getPdo()->lastInsertId();
      $ins_user = array(
          "person_id"=>$id,
          "name"=>$fname." ".$lname,
          "email"=>$email,
          "username"=>$email,
          "password"=> bcrypt($password),
          "is_active"=> 1,
          "is_verified"=> 1,
          "is_mobile_user"=> 1,
          "is_desktop_user"=> 1,
          "created_at"=> date('Y-m-d h:i:s')
          );
      DB::table('users')->insert($ins_user);
      $dt['st']=1;
      $dt['msg']='Registration Completed Successfully';
      echo json_encode($dt);
   }
    
  
}
