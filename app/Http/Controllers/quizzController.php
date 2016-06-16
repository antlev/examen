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
class quizzController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() //Verifi que la personne soit authentifié
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
  
  public function insertQuestions() { //Insert les questions eb base
    $data = Input::get('insertQ');
    $data = json_decode($data);
      DB::table('quizz')->truncate();
      foreach($data->values as $key => $questions) {
          DB::table('quizz')
              ->insert([
              ['nom' => $questions->data->nom ]
          ]);
      }
  }

    public function QuestionReponse() { //Permet d'inserer les réponses en base

        $data = Input::get('QuestionReponse');
        $data = json_decode($data);
         $Question = array();
        DB::table('quizz_reponse')->truncate();
        foreach($data->values as $key => $questionreponse) {

            array_push($Question, array('BonneReponse' => $questionreponse->data->BonneReponse, 'question' => $questionreponse->data->Question));
            DB::table('quizz_reponse')->insert([
                ['id_reponse'=>$questionreponse->data->Reponse,'reponse_reponse' => $questionreponse->data->PhraseReponse,'idquestion_reponse'=>$questionreponse->data->Question]
            ]);
        }
        foreach($Question as $q) {
            $idquestion = $q['question'];
            if($q['BonneReponse']!=0) {
                DB::table('quizz')
                    ->where('id', $idquestion)
                    ->update(array('idreponse' => $q['BonneReponse']));

            }
}
            return '1';
    }
   // public function getTheme($id) {
   //     $theme = DB::table('sports')->where('id_sports', $id)->first();
   //     return $theme;
//
   // }
    public function checkAnswer2() {// Permet de verifier les bonne réposnes
        $data = Input::get('Answer');
        $data = json_decode($data);
        $resultJson=array();
        foreach($data->values as $key=>$Results) {

      array_push($resultJson, array("idQuestion"=>$Results->data->question,"idreponse"=>$Results->data->reponse));
                 }

       $BonneReponse = array();
        $Reponse = array();

   //  for($i=0;$i<sizeof($resultJson);$i++) {

      //  $id_reponse = str_replace("r_","",$resultJson[$i]['idreponse']);
        $resultResponse = DB::table('quizz')
            ->join('quizz_reponse', 'quizz_reponse.idquestion_reponse', '=', 'quizz.id')
            ->orderBy('quizz.id', 'desc')->get();


        //DB::table('quizz')->get();
            //   ->where('id', $resultJson[$i]['idQuestion'])
            //  ->where('idreponse',$id_reponse)

        foreach ($resultResponse as $quizzReponse) {
            $id = $quizzReponse->id;
            $idreponse = $quizzReponse->idreponse;
            $reponse_id = $quizzReponse->id_reponse;
            $reponse_nom = $quizzReponse->reponse_reponse;
            array_push($Reponse, array('idQuestion' => $id,'idReponse' => $reponse_id,'BonneReponse'=>$idreponse));

        }
        for($i=0;$i<count($Reponse);$i++) {
            array_push($BonneReponse, array('idQuestion' => $Reponse[$i]['idQuestion'], 'idReponse' => $Reponse[$i]['idReponse'], 'BonneReponse' => $Reponse[$i]['BonneReponse']));



        }
        // array_push($BonneReponse, array("idquestion",$resultJson[$i]['idQuestion'],"idreponse"=>$id_reponse));
    //   }
        return json_encode($BonneReponse);
    }

    static public function checkAnswer() {
        $question = Input::get('question');
        $reponse = Input::get('reponse');


            $resultat = DB::table('quizz')
                ->where('id', $question)
                ->where('idreponse', $reponse)
                ->first();


         return json_encode($resultat);
    }
    public function getgame() {
        $quizz = DB::table('quizz')
            ->join('quizz_reponse', 'quizz_reponse.idquestion_reponse', '=', 'quizz.id')
            ->orderBy('quizz.id', 'desc')->get();
        $quizzJson = array();
        foreach ($quizz as $quizzresult) {
            $id = $quizzresult->id;
            $nom = $quizzresult->nom;
            $reponse_id = $quizzresult->id_reponse;
            $reponse_nom = $quizzresult->reponse_reponse;
            $reponse_quesiton = $quizzresult->idquestion_reponse;
           // $categorie = $this->getCategorie($id);
            //$theme = $this->getTheme($categorie->theme_categorie);

            array_push($quizzJson, array('id' => $id,'nom' => $nom,'reponseid'=>$reponse_id,'choix'=>$reponse_nom));

        }
        return json_encode($quizzJson);


    }
    public function getQuestions() {
        $quizz = DB::table('quizz')->orderBy('id', 'asc')->get();
        $quizzJson = array();

        foreach ($quizz as $quizzresult) {
            $id = $quizzresult->id;
            $nom = $quizzresult->nom;
            array_push($quizzJson, array('id' => $id,'nom' => $nom));

//echo 'nickname: '.Auth::getNameById($shoutresult->iduser_shoutbox).' msg : '.$shoutresult->msg_shoutbox;
   }
return json_encode($quizzJson);
    }

public function quizzReponse()
    {
        return view('reponse');
    }
    public function gamepage() {

        return view('quizz_game');

    }
    public function index()
    {
        return view('quizz');
    }
}
