<?php

namespace App\Http\Controllers;
use Input;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Auth;   
use Validator;
use Redirect;
use Response;
use Cache;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
public function getProfil() {

        return view('profil');
}

public function postImage() {
      
        $data = Input::all();
       
        $result =  DB::table('users')->where('id',Auth::id())->update(array('photo' => $data['img']));

        return $result;

}
public function changeUserInfo() {
        $data = Input::all(); 

       Auth::changeUserInfo($data['column'],$data['info']);

}

public function testhome() {

    return 'ok';

}

public function getShouts() { 
 $shout = DB::table('shoutbox')->orderBy('id', 'asc')->get();
 $user = array();
foreach ($shout as $shoutresult) {

    $name = Auth::getNameById($shoutresult->iduser_shoutbox);
    $date = $shoutresult->date_shoutbox;
    $message = $shoutresult->msg_shoutbox;
    $sport = Auth::getSportbyId($shoutresult->iduser_shoutbox);
   // $color = Auth::getColorById(Auth::getRoleById($shoutresult->iduser_shoutbox));
    array_push($user, array('name' => $name,'date' => $date,'msg' => $message,'sport'=>$sport));

//echo 'nickname: '.Auth::getNameById($shoutresult->iduser_shoutbox).' msg : '.$shoutresult->msg_shoutbox;
   }
    echo json_encode($user);

}
public function sbInsert(){

    $data = Input::all(); 
    DB::table('shoutbox')->insert([
            ['date_shoutbox' => $data['date'],'msg_shoutbox'=>$data['msg'],'iduser_shoutbox'=>$data['iduser']]
        ]);

}
    public function getAllMembers() {
        $mbers = DB::table('users')->orderBy('id', 'asc')->get();

        $users_info=array();
        foreach ($mbers as $members) {
            $id = $members->id;
            $name = $members->name;
            $ville = $members->ville;
            $email = $members->email;
            $datecreated = $members->created_at;
            $datenaissance = $members->datenaiss;
            $sport =  $members->sport;
            $photo = $members->photo;
            $civilite = $members->civilite;
            array_push($users_info, array('id'=>$id,'name' => $name,'date' => $datecreated,'civilite'=>$civilite,'ville'=>$ville,'photo'=>$photo,'email'=>$email,'sport'=>$sport,'datenaissance'=>$datenaissance));

        }
        return $users_info;

    }
    public function userProfil() {
        return view('userprofil');

    }
    public function sendPrivMsg() {
        $data = Input::all();
        $date = date('Y-m-d h:i:s');
        DB::table('users_message')->insert([
            ['destinataire' => $data['to'],'emetteur'=>$data['from'],'message'=>$data['message'],'date'=>$date]
        ]);


    }
    public function MembersbyId($id) { 

               $users = DB::table('users')->where('id', $id)->first();
            $users_info = array();  

            $id = $users->id;
            $name = $users->name;
            $nom= $users->nom;
            $prenom = $users->prenom;
            $datenaissance = $users->datenaiss;
            $ville = $users->ville;
            $nationalite = $users->nationalite;
            $email = $users->email;
            $datecreated = $users->created_at;
            $sport =  $users->sport;
            $photo = $users->photo;
            $classement = $users->classement;
            $civilite = $users->civilite;
            array_push($users_info, array('id'=>$id,'name' => $name,'nom'=>$nom,'prenom'=>$prenom,'datenaiss'=>$datenaissance,'nationalite'=>$nationalite,'classement'=>$classement,'date' => $datecreated,'civilite'=>$civilite,'ville'=>$ville,'photo'=>$photo,'email'=>$email,'sport'=>$sport));

        $users_info = array('userinfo'=>$users_info);
        return view('profil_user',$users_info);
 
    }
   
    public function readMsg() {

        $data = Input::all();
        DB::table('users_message')->where('id', $data['id'])->update(array('lu' => 1));
        return 'ok';
    }
    public function getPrivMsg() {
        $data = Input::all();
        $msg = array();

        $message = DB::table('users_message')
            ->where('emetteur',$data['id'])
            ->where('destinataire',Auth::id())
            ->where('date',$data['date'])
            ->get();

        foreach ($message as $messages) {
            $id = $messages->id;
            $nomid = Auth::getNameById($messages->emetteur);
            $date = $messages->date;
            $message = $messages->message;
            array_push($msg, array('id'=>$id,'nom'=>$nomid,'date'=>$date,'message'=>$message));
        }
            return json_encode($msg);


    }
public function Members()
{
    $data = array('data'=>$this->getAllMembers());

    return view('members',$data);
}
    public function Register() {
        return view('auth.register');

    }
    public function index()
    {
        return view('welcome');
    }
}
