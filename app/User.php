<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // public function mail(user_id, id_planning, $action, $request){
    //     $from = DB::select("SELECT distinct email, concat(nom,' ',prenom) as 'nom'  from users where id = '".$user_id."'");
    //     $roles = DB::select('SELECT distinct r.nom, r.id  from planning p join participants pa on pa.id_planning = p.id join roles r on r.id = pa.id_roles where p.id = "{$id_planning}"');
    //     Mail::send('email', $data, function($mess){
    //         $mess->from($from['email]', $from['nom']);
    //         foreach ($roles as $role) {
    //             $and = '';
    //             if($roles[$role]['nom'] == 'Joueurs'){
    //                 $and = " AND sport = '{$request['sport']}' AND categorie = '{$request['categorie']}'";
    //             }
    //             $email = "SELECT distinct email, concat(nom,' ',prenom) as 'nom'  from user_roles ur join users u on u.id = ur.user_id where ur.id_roles = '{$roles[$role]['id']}' {$and}";
    //             foreach ($email as $key ) {
    //                 $mess->to($email[$key]['email'], $email[$key]['nom']);
    //             }                
    //         }

    //     });
    // }
}
