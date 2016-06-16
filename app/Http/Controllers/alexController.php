<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Mail;
use Session;
use Auth;

class alexController extends Controller
{
   	public function __construct()
    {
    	// Vérification de l'autentification de l'utilisateur 
        $this->middleware('auth');
    }

    public function getInfos()
    {            
        // Retourne la vue de formulaire
        return view('formulaire');
    }
    public function get_formulaire(){
        $user = DB::table('users')
        ->where('id', Auth::id())->select('nom as name', 'prenom as prename','telephone as fix','civilite', 'mobile as perso','datenaiss as datnaiss','sport','categorie','adresse as addr','cp as codpost','ville as town','nationalite as nat')
        ->get();
        return json_encode($user[0]);
    }

    // Mise a jour des données de l'utilisateur
    public function postInfos(Request $request)
    {
    	// Instanciation de l'objet
        $user = Auth::user();
        $tabs = array('civilite', 'nom','prenom','telephone','mobile','datenaiss','sport','categorie','adresse','cp','ville','nationalite');
        // Incrémentation des données utilisateurs
        foreach ($tabs as $tab) {
        	// Changement de la date pour la base donnée 
            if($tab == 'datenaiss'){
                $ex = explode("/", $request[$tab]);
                $user->$tab = $ex[2].'-'.$ex[1].'-'.$ex[0];
            }
            else{$user->$tab = $request[$tab];}
        }
        if($request['classement']){$user->classement = $request['classement'];}
        // On récupère l'id du role joueur et met a jour sa table relationnel
        $id_role = $this->getId('roles','Joueurs', 'id');
        DB::table('user_roles')->where('user_id', $user->id)->update(array('id_role' => $id_role));
        $user->save();
        // On retourne la page d'acceuil
        return view('welcome');
    }

    // Envoie d'un mail pour la plannification
    public function mail($user_id, $id_planning, $event, $data=''){
    	// On récupère le nom et le mail de celui qui à crée la plannif
        $from = DB::select("SELECT distinct email, concat(nom,' ',prenom) as 'nom'  from users where id = '".$user_id."'");
        // On récupère les infos de la plannifs
        $data = DB::select('SELECT type, nom "name", lieu_name as lieu, DATE_FORMAT(debut, "%d/%m/%Y") day, DATE_FORMAT(debut, "%k:%i") ad, DATE_FORMAT(fin, "%k:%i") af, sport, description
                            FROM planning
                            where id ='.$id_planning);
        $categorie = explode(' ', $data[0]->name);
        $categorie = ($data[0]->type == 'Entrainement' || $data[0]->type == 'Tournoi' || $data[0]->type == 'Match' )? $categorie[1] : '';
        $data[0]->categorie = $categorie;
        $add = array('categorie' => $categorie, 'sport' =>$data[0]->sport);
        // Format de donnée selon les infos envoyées
        if($event == 'modif'){
            $subject = 'Modification de votre calendrier';
            $data[0]->event = 'modifié';
        }
        else if ($event == 'creation'){
            $subject = 'Ajout d\'une plannification';
            $data[0]->event = 'ajouté';
        }
        else{
	        $subject = 'Suppréssion d\'une plannification';
	        $data[0]->event = 'supprimé';
        }
        $data[0]->nom = $from[0]->nom;
        // Définition des roles qui concerne la plannif
        $roles = DB::select("SELECT distinct id_role from participants where id_planning = ".$id_planning);
        // Appel de l'objet mail pour l'envoyer
        Mail::send('email', ['data'=> $data[0]] , function($mess) use ($from,$subject,$roles,$add){
        	// Déstinataire principal puis sujet du mail
            $mess->to($from[0]->email, $from[0]->nom);
            $mess->subject($subject);
            // On parcourt les roles pour avoir les autres déstinataires
            foreach ($roles as $role => $value) {
                $and = '';
                if($value->id_role == $this->getId('roles', "Joueurs", 'nom')){
                    $and = " AND u.sport = '".$add['sport']."'";
                	if(!empty($add['categorie'])){
                    	$and .= "AND u.categorie = '".$add['categorie']."'";                		
                	}
                }
                // Récupère les infos de chaque utilisateur
                $email = DB::select("SELECT distinct email, concat(nom,' ',prenom) as 'nom'  from users u join user_roles ur on u.id = ur.user_id where ur.id_role = '".$value->id_role."'".$and);
                if(empty($email)){continue;}
                foreach ($email as $key => $val) {
                	// Choix du destinataire
                    $mess->to($val->email, $val->nom);
                }                
            }
        });
    }

    // Retourne la vue du calendrier
    public function getCalendar(){
        return view('fullcalendar');
    }
    // return l'id que l'on a demandé
    public function getId($table, $name, $field){
        return DB::table($table)->where($field, $name)->value('id');
    }
    // Mise à jour de la BDD en fonction du retour de la plannif
    public function postCalendar(){
    	// Suppression d'une plannif
        if ($_GET['action'] == 'delete'){
            // Verification pour savoir si l'événement et passé ou non
            $passe = DB::select(
                "SELECT 
                CASE 
                    WHEN fin > now() then 1
                    WHEN fin < now() then 0
                END 'passe'
                from planning
                where id = ".$_GET['id']);
            if($passe[0]->passe == 1){$this->mail(Auth::id(), $_GET['id'], 'delete');}
            DB::table('participants')->where('id_planning', $_GET['id'])->delete();
            DB::table('planning')->where('id', $_GET['id'])->delete();
        }
        else{
        	// Création de la plannif
            if($_GET['action'] == 'insert'){
                $nom = (!empty($_GET['categorie'])) ? $_GET['type'].' '.$_GET['categorie']." ".$_GET['sport']." ".$_GET['lieu'] : $_GET['type']." ".$_GET['sport']." ".$_GET['lieu'];
                $lieu_id = DB::select('SELECT distinct id from lieux where nom = "'.$_GET['lieu'].'"');
                DB::table('planning')->insert([
                    ['nom' => $nom, 'debut' => $_GET['debut'], 'fin' => $_GET['fin'],'sport'=> $_GET['sport'], 'type' => $_GET['type'], 'lieu_name' => $_GET['lieu'], 'lieu_id' => $lieu_id[0]->id, 'user_id' =>Auth::id(), 'description' => $_GET['description'],'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
                ]);
                $id_planning = DB::getPdo()->lastInsertId();
                $id_role = $this->getId('roles', "Joueurs", 'nom');
                $ok = 0;
                foreach (Auth::getRoles() as $key => $values) {
                    if($values->id_role == $this->getId('roles', "Entraineurs", 'nom')){$ok = 1;}
                }
                if($_GET['sport'] != 'aucun' && $ok == 1){
                	echo "je participants joueur";
                    DB::table('participants')->insert([
                        [ 'id_planning' => $id_planning, 'id_role' =>  $id_role, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
                    ]);
                }
                // else{
                //     foreach ($_GET['contact'] as $key => $value) {
                //         DB::table('participant')->insert([
                //             ['id_planning' => $id_planning, 'id_role' => $value, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
                //         ]); 
                //     }
                // }
                $this->mail(Auth::id(), $id_planning, 'creation');
            }
            // Modification de la date et envoie de mail
            else if($_GET['action'] == 'drop'){
                DB::table('planning')->where('id', $_GET['id'])
                ->update(array('debut'=>$_GET['debut'] , 'fin'=> $_GET['fin'],'updated_at' => date('Y-m-d H:i:s')));
                $this->mail(Auth::id(), $_GET['id'], 'modif');
            }
            //  Modification de la fin de la plannif et envoie de mail
            else if ($_GET['action'] == 'resize'){
                DB::table('planning')->where('id', $_GET['id'])
                ->update(array('fin'=> $_GET['fin'],'updated_at' => date('Y-m-d H:i:s')));   
                $this->mail(Auth::id(), $_GET['id'], 'modif');
            }
            // Duplication d'une plannif et envoie de mail
            else if ($_GET['action'] == 'copy'){
                DB::select("INSERT INTO planning (debut,fin,nom,type,lieu_name,lieu_id,description,user_id,sport,created_at, updated_at)
                    SELECT '".$_GET['debut']."', '".$_GET['fin']."', nom,type,lieu_name,lieu_id,description,user_id,sport,created_at, updated_at
                    from planning
                    where id =".$_GET['id']);
                $roles = DB::select("SELECT distinct r.nom, r.id  from planning p join participants pa on pa.id_planning = p.id join roles r on r.id = pa.id_role where p.id = ".$_GET['id']);
                $id = DB::table('planning')->max('id');
                foreach ($roles as $role => $value) {
                	DB::table('participants')->insert([
                		['id_planning' => $id, 'id_role' => $this->getId('roles', $value->nom, 'nom'), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
                	]);
                }
                $this->mail(Auth::id(), $id, 'modif');
            }
            // Modification complète de la plannif et envoie de mail
            else{
                $nom = (!empty($_GET['categorie'])) ? $_GET['type'].' '.$_GET['categorie']." ".$_GET['sport']." ".$_GET['lieu'] : $_GET['type']." ".$_GET['sport']." ".$_GET['lieu'];
                $lieu_id = DB::select('SELECT distinct id from lieux where nom = "'.$_GET['lieu'].'"');
                DB::table('planning')->where('id', $_GET['id'])
                ->update(array('nom'=> $nom, 'debut'=> $_GET['debut'], 'fin' => $_GET['fin'],'sport'=> $_GET['sport'], 'type' => $_GET['type'], 'lieu_name' => $_GET['lieu'], 'lieu_id' => $lieu_id[0]->id, 'description' => $_GET['description'],'updated_at' => date('Y-m-d H:i:s')));
                $this->mail(Auth::id(), $_GET['id'], 'modif');
            }
        }
    }

    public function postIndex(){
        $name = "(";
        $value = "(";
        $update = array();
        //Parcourt du tableau pour la remise en forme avant la création
        foreach($_GET['create'] as $key => $val){
            if($_GET['table'] == 'user_roles'){
                $val['value'] = ($val['name'] == 'role') ? $this->getId('roles',$val['value'], 'nom') : $this->getId('users', $val['value'], 'name');
                $val['name'] = ($val['name'] == 'role') ? 'id_role' : 'user_id';
            }
            else if($_GET['table'] == 'participants'){
                $val['value'] = ($val['name'] != 'nom_planning') ? $this->getId('roles',$val['value'], 'nom') : $this->getId('planning', $val['value'], 'nom');
                $val['name'] = ($val['name'] != 'nom_planning') ? 'id_role' : 'id_planning';
            }
            
            if($val['name'] == 'datenaiss' && !empty($val['value'])){
                $ex = explode("/", $val['value']);
                $val['value'] = $ex[2].'-'.$ex[1].'-'.$ex[0];
            }


            if($_GET['action'] == 'update'){
                $update = array_merge(array($val['name'] => $val['value']), $update);
            }
            else{
                $name .= $val['name'].',';
                $value .= '"'.$val['value'].'"'.',';                
            }
        }
        if($_GET['action'] == 'update'){
            $update = array_merge(array('updated_at' => date('YmdHis')), $update);
            DB::table($_GET['table'])->where('id', $_GET['id'])->update($update);
        }
        else{
            $name .= "created_at, updated_at)" ;
            $value .= date('YmdHis').",".date('YmdHis').")";
            DB::select("insert into {$_GET['table']} {$name} values {$value}");
        }
    }

    // Envoie des différentes données dans la vue liste
    public function index($table){
    	// Préparation du listage des 100 éléments de la table
        $types = DB::table($table)->paginate(100);
        // Traitement des données à afficher
        if($table == 'user_roles'){
            foreach ($types as $key => $value) {
                $role = DB::table('roles')->where('id', $value->id_role)->value('nom');
                $user = DB::select('SELECT distinct nom, prenom , name from users where id = "'.$value->user_id.'"');
                $types[$key]->role = $role;
                $types[$key]->nom = $user[0]->nom;
                $types[$key]->name = $user[0]->name;
                $types[$key]->prenom = $user[0]->prenom;
                
            }
        }
        else if($table == 'participants'){
            foreach ($types as $key => $value) {
                $role = DB::table('roles')->where('id', $value->id_role)->value('nom');
                $planning = DB::table('planning')->where('id', $value->id_planning)->value('nom');
                $types[$key]->role = $role;
                $types[$key]->nom_planning = $planning;
            }
        }
        else if($table == 'users'){
            for ($i=0; $i < $types->total(); $i++) { 
                if(!empty($types[$i]->datenaiss)){
                    $ex = explode("-", $types[$i]->datenaiss);
                    $types[$i]->datenaiss = $ex[2].'/'.$ex[1].'/'.$ex[0];                
                    
                }
            }
        }
        // On récupère le nom des colonnes et si il sont éditable ou non
        $name = $this->getField($table);
        $return = array(
            'types' => $types,
            'names' => $name,
            'table' => $table
        ); 
        // On renvoi le tout dans la vue liste
        return View('view_list', $return);
    }
    // Retourne une liste de champs en fonction de la table
    public function getField($table){
        $retour = array(
            'users' => array('id'=> 'nok','nom'=> 'ok','prenom'=> 'ok','name'=> 'ok', 'email'=> 'ok','datenaiss'=> 'ok','sport'=> 'ok','categorie'=> 'ok','mobile'=> 'ok','adresse'=> 'ok','ville'=> 'ok','cp'=>'ok','admin'=> 'ok'),
            'roles' => array('id'=> 'nok', 'nom'=> 'ok','created_at'=> 'nok','updated_at'=> 'nok'),
            'lieux' => array('id'=> 'nok', 'nom'=> 'ok', 'sport'=> 'ok','adresse'=> 'ok', 'created_at'=> 'nok','updated_at'=> 'nok'),
            'planning' => array('id' => 'nok', 'nom' => 'ok', 'debut'=>"ok",'fin' => 'ok','type' => 'ok','sport' => 'ok','lieu_name'=> 'ok','description'=> 'ok','created_at'=> 'nok','updated_at'=> 'nok'),
            'user_roles' => array('id'=> 'nok', 'id_role'=> 'nok', 'user_id'=> 'nok', 'role'=> 'ok','name'=> 'nok','nom'=> 'nok', 'prenom'=> 'nok', 'created_at'=> 'nok', 'updated_at'=> 'nok'),
            'participants' => array('id'=> 'nok','id_role'=> 'nok', 'id_planning'=> 'nok','role'=> 'ok','nom_planning'=> 'nok','created_at'=> 'nok','updated_at'=> 'nok'),
        );
        return $retour[$table];
    }
    // On renvoie les éléments d'autocomplétion 
    public function autocomplete(){
        if($_GET['action'] == 'autocomplete'){
            if($_GET['table'] == 'users'){
                $lieux = DB::select('SELECT distinct name as "nom" from users');
            }
            else if ($_GET['table'] == 'participants'){
                $lieux = DB::select('SELECT distinct nom from planning');   
            }
            else{$lieux = DB::select('SELECT distinct '.$_GET["nom"].' from '.$_GET["table"].' where '.$_GET["name"].' ="'.$_GET['type'].'"');}
        }
        else{
            $lieux = DB::select('SELECT lieu_name as "lieu", count(*) "nb" from planning where debut between "'.$_GET['debut']
                .'" and "'.$_GET['fin'].'" OR fin between "'.$_GET['debut'].'" and "'.$_GET['fin'].'" GROUP BY lieu_id');
        }
        // On renvoie le tout en json
        return json_encode($lieux);            
    }
    // Ajout d'un événement utilisateur dans son calendrier
    public function add_event(){
        $user = Auth::user();
        $or = "";
        // On parcourt les roles de l'utilisateur 
        foreach (Auth::getRoles() as $key => $values) {
        	// Si c'est un joueur on affiche les plannifs de sa catégorie
            if($values->id_role == $this->getId('roles','Joueurs', 'nom')){
            	// On récupère la liste d'id de plannif de sa catégorie
                $id  = DB::select('SELECT p.id from planning p join participants pa on pa.id_planning = p.id where pa.id_role = 
                    (SELECT distinct id from roles where nom = "Joueurs") and sport ="'.$user->sport.'"
                    AND (p.nom like "%'.$user->categorie.'%" OR p.type not in ("Entrainement","Match","Tournoi")) 
                    AND user_id <>'.$user->id);
                $ids = "";
                foreach ($id as $key => $value) {
                    $ids .= $value->id.' ,';
                }
                $ids = substr($ids, 0, -1);
                if(!empty($ids) && strlen($ids) > 1)     {$or = "OR id in (".$ids.") ";}
                else{$or = "";}
            }
        }
        // On récupère toute ses plannifs
        $event = DB::select('SELECT id, nom, debut, fin, lieu_name as "lieu", type, description, sport, 
            CASE 
                WHEN user_id = '.$user->id.' then 1 
                WHEN user_id <> '.$user->id.' then 0
            END as "modif"
         from planning where user_id = "'.$user->id.'"'.$or);
        // On envoie le tout dans le calendrier
        return json_encode($event);
    }
    // Mise a jour des données de la vue liste en click edit
    public function update(){
        // On parse la date le format de la base de données
        if($_GET['name'] == 'datenaiss' && !empty($_GET['value'])){
            $ex = explode("/", $_GET['value']);
            $_GET['value'] = $ex[2].'-'.$ex[1].'-'.$ex[0];
        }
        // Exception pour l'id du role 
        $value = ($_GET['name'] == 'id_role') ? $this->getId('roles',$_GET['value'],'nom') : $_GET['value'];
        // Mise à jour de la base de donnée 
        DB::table($_GET['table'])->where('id', $_GET['id'])->update(array($_GET['name'] => $value, 'updated_at' => date('Y-m-d H:i:s')));
    }

    // Suppréssion des éléments séléctionnés
    public function supp_view(){
    	// Suppression des éléments dans la table relationnel
        if($_GET['table'] == 'users'){
            DB::table('user_roles')->where('user_id', $_GET['data'][1])->delete();
            DB::table('planning')->where('user_id', $_GET['data'][1])->delete();
            DB::table('users')->where('id', $_GET['data'][1])->delete();
        }
       	else if ($_GET['table'] == 'lieux'){
       		// Mise à jour des plannings associé pour vider le lieu renseigné
            DB::table('planning')->where('lieu_id', $_GET['data'][1])->update(array('lieu_id' => '', 'updated_at' => date('Y-m-d H:i:s')));
            DB::table('lieux')->where('id', $_GET['data'][1])->delete();
       	}
       	else if($_GET['table'] == 'roles'){
            DB::table('participants')->where('id_role',$_GET['data'][1])->delete();
            DB::table('user_roles')->where('id_role', $_GET['data'][1])->delete();
            DB::table('roles')->where('id', $_GET['data'][1])->delete();
       	}
       	else if($_GET['table'] == 'planning'){
            DB::table('participants')->where('id_planning',$_GET['data'][1])->delete();
            DB::table($_GET['table'])->where('id', $_GET['data'][1])->delete();
       	}
       	else{
            DB::table($_GET['table'])->where('id', $_GET['data'][1])->delete();
       	}
    }
}