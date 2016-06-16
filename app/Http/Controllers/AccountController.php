<?php 

namespace App\Http\Controllers;
use Input;
use Request;
use DB;
use Hash;
use Session;
class AccountController extends Controller {


  public function getLogin() {
    // Getting all post data
   return View('login');
}

   public function postLogin() { 
   if(Request::ajax()) { 
    $data = Input::all(); 

    $usr = Session::put('mail', $data['email']);
    $pwd = Session::put('password', hash('md5', $data['password']));

     $db = DB::select("SELECT user_login,user_password FROM base_m2l WHERE user_password = '$pwd' AND user_login = '$usr'");

     echo 'ok';

  }

} 
}