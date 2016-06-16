<?php
namespace App\Http\Controllers;

use DB; // Allow to use DB request
use Input;
use Session;
use App\Http\Controllers\Controller;
use App\dataForum;
use Auth; // Allowto use directly Auth methods
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector; // Contains the methods used to redirect url

class forumController extends Controller{

	// Checks the authentification of the user, the user must be identified to access the forum
	public function __construct()
	{
		$this->middleware('auth');
	}
	/**
	Functions used to return the views
	All these functions get the necessary data and
	return the view passing all the data in an array
	 */
	// Return the forumIndexView with correct data (route : /forum/)
	public function index(){
		$forum = $this->__getForum();
		$categories = $this->__getAllCategories(); // Get all categories for nav
		$topics = $this->__getAllTopics(); // for navigation
		$nbTopic = array(); // Store the number of topic for each categorie
		$nbPost = array(); // Store the number of post for each categorie
		$lastPost = array(); // Store data about the last post
		$lastPostCreator = array(); // Store the name of the last post creator
		// GETTING ALL INFORMATION
		foreach ($categories as $cat) {
			array_push($nbTopic, $this->getNbTopicByCat($cat->cat_id));
			array_push($nbPost, $this->getNbPostByCat($cat->cat_id));
			// First we get the last post creator id then his name using Auth
			// If the lastPost or lastPostCreator is null, then we put -1 into the var
			if( Auth::getNameById($this->__getLastPostCreatorIdByCat($cat->cat_id)->post_createur) != null){
				array_push($lastPostCreator, Auth::getNameById($this->__getLastPostCreatorIdByCat($cat->cat_id)->post_createur));
			} else {
				array_push($lastPostCreator, -1);
			}
			if( $this->__getLastPostByCat($cat->cat_id) != null){
				array_push($lastPost, $this->__getLastPostByCat($cat->cat_id));
			} else {
				array_push($lastPost, -1 );
			}
		}

		// Puts information into an array to send everything to 'forumIndexView'
		$data = array(
			'forum' =>  $forum,
			'categories' =>  $categories,
			'nbTopic' => $nbTopic,
			'nbPost' => $nbPost,
			'lastPost' => $lastPost,
			'lastPostCreator' => $lastPostCreator,
			'topics' => $topics);
		// Return the view passing $data as a parameter

		return view('forumIndexView', $data);
	}
	// Return the forumCatView with correct data (route : /forum/{cat})
	public function cat($cat){
		$categories = $this->__getAllCategories(); // for the navigation menu
		$catName = $this->__getCatName($cat); // Get the categorie name using the id
		$topic = $this->__getTopicFromCatLimit($cat,0); // Return a fixed number of topics starting at the id which is the second parameter
		$topics = $this->__getAllTopics(); // Return all topics (for navigation)
		//$lastTopicFromCat = $this->__getLastTopicIdByCat($cat)->topic_id; // Return last topic from categorie

		// Puts information into an array to send everything to 'forumIndexView'
		$data = array(
			'topic' => $topic,
			'topics' => $topics,
			//'lastTopicFromCat' => $lastTopicFromCat,
			'categories' =>  $categories,
			'catName' => $catName,
			'cat' => $cat, // (passed as a parameter)
		);

		return view('forumCatView',  $data);
	}
	// Return the forumTopicView with correct data (route : /forum/{cat}/{topic})
	public function topic($cat,$topic_id){
		$categories = $this->__getAllCategories(); // for the navigation menu
		$posts = $this->__getPosts($topic_id);
		$catName = $this->__getCatName($cat); // Return the categori name using it's id
		$topic = $this->__getTopic($topic_id);
		$topics = $this->__getAllTopics();
		$data = array(
			'posts' => $posts,
			'catName' => $catName,
			'topic' => $topic,
			'topics' => $topic,
			'cat' => $cat,
			'categories' => $categories);
		return view('forumTopicView', $data);
	}
	// Return the forumNewTopicView with correct data (route : /forum/{cat}/newTopic)
	public function newTopic($cat){
		$categories = $this->__getAllCategories(); // for the navigation menu
		$catName = $this->__getCatName($cat); // Get the parent categorie name
		$topic = $this->__getTopicFromCat($cat); // Get all the topics in the categorie passed as a parameter
		$topics = $this->__getAllTopics(); // Get all topics
		$data = array(
			'categories' => $categories,
			'cat' => $cat,
			'catName' => $catName,
			'topics' => $topics,
			'topic' => $topic);
		return view('forumNewTopicView',$data);
	}
	// Return the newPostView with correct data (route : )
	public function newPost($cat,$topic_id){
		$categories = $this->__getAllCategories(); // Get all categories for nav
		$topics = $this->__getAllTopics(); // Get all topics
		$data = array(
			'categories' => $categories,
			'cat' => $cat,
			'topic_id' => $topic_id,
			'topics' => $topics);
		return view('forumNewPostView',$data);
	}
	// Return the forumMyPostsView with correct data (route : /forum/{auth}/myPosts)
	public function myPosts($auth){
		$categories = $this->__getAllCategories(); // for the navigation menu
		$postCat = array();
		$posts = $this->__getPostByCreatorId($auth);
		foreach ($posts as $post) {
			if($this->__getCatByPost($post->post_id) != null){
				array_push($postCat, $this->__getCatByPost($post->post_id));
			} else{
				array_push($postCat, array(-1));
			}
		}
		$nbPost = $this->__getNbPostByCreatorId($auth);
		$data = array(
			'categories' => $categories,
			'posts' => $posts,
			'nbPost' => $nbPost,
			'postCat' => $postCat);
		// Checks the authenticity of the user
		if( Auth::isAdmin() || Auth::id() == $auth ){  // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post
			return view('forumMyPostsView', $data);
		} else {
			dd("Vous n'avez pas le droit d'accèder à cette page");
		}
	}
	// Return forumMyProfilView (route : /forum/{auth}/myProfil )
	public function myProfil($auth){
		$categories = $this->__getAllCategories(); // for the navigation menu
		$data = array();
		$nbPost = $this->__getNbPostByCreatorId($auth);
		$nbTopic = $this->__getNbTopicByCreatorId($auth);

		$data = array(
			'categories' => $categories,
			'nbPost' => $nbPost,
			'nbTopic' => $nbTopic,
			'userId' => $auth);

		// Checks the authenticity of the user
		if( Auth::isAdmin() || Auth::id() == $auth ){  // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post
			return view('forumMyProfilView', $data);
		} else {
			dd("Vous n'avez pas le droit d'accèder à cette page");
		}
	}
	// Return the adminView (route : /forum/admin)
	public function adminView(){
		$categories = $this->__getAllCategories(); // for the navigation menu
		if( Auth::isAdmin() ){ // We checks that the user is an admin
			return view('forumAdminView', $categories);
		} else { // If not we return the index
			return $this->index();
		}
	}
	// Function called in gt by 'routes' which return the 'forumEditPostView'
	public function editPostView($cat,$topicId,$postId){
		$categories = $this->__getAllCategories(); // for the navigation menu
		// Checks that user has the correct right pn the post
		$postToEdit = $this->__getPostMessageById($postId)[0]->post_texte;
		$data = array(
			'categories' => $categories,
			'postId' => $postId,
			'cat' => $cat,
			'topic_id' => $topicId,
			'postToEdit' => $postToEdit);
		if( Auth::isAdmin() ){
			return view('forumEditPostView',$data);
		} else if( Auth::id() == $this->__getCreatorPostById($post_id)[0] ){ // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post
			return view('forumEditPostView',$data);
		} else {
			dd("Vous n'avez pas le droit d'accèder à cette page");
		}
	}

	/**
	Methods called by the jQuery Posts requests
	 */
	// Return the next pages to print for the forumCatView
	public function getPostInfoById(){
		$topicData = Input::get('topicData');
		$creatorName = array();
		$nbPost = array();
		$lastPostDate = array();
		// dd($topicData);
		for($i = 0 ; $i < sizeof($topicData['topicId']) ; $i++){
			$creatorName[] = Auth::getNameById($topicData['creator'][$i]);
			$nbPost[] = $this->__getNbPostByTopic($topicData['topicId'][$i]);
			array_push($lastPostDate, $this->__getLastPostDateByTopic($topicData['topicId'][$i]));
		}
		$data = array(
			'topicData' => $topicData,
			'creatorName' => $creatorName,
			'nbPost' => $nbPost,
			'lastPostDate' => $lastPostDate);

		// dd($data);

		// json_encode while encode data to be usable in the jQuery request
		return json_encode($data);
	}
	public function nextCat($cat){
		$inputData = Input::all(); // Getting data from Post_Request
		$firstTopicToReturn = $inputData['lastTopicPrinted']+1;
		// __getTopicFromCatLimit while return a certain number of topic starting at firstTopicToReturn
		$topics = $this->__getTopicFromCatLimit($cat,$firstTopicToReturn);

		$data = array(
			'topics' => $topics);
		// json_encode while encode data to be usable in the jQuery request
		return  json_encode($data);
	}

	/**
	Methods called from the views
	 */
	public function getNbPostByCat($cat){
		return DB::table('forum_post')
			->select()
			->distinct()
			->join('forum_topic', 'forum_post.post_topic_id', '=', 'forum_topic.topic_id')
			->where('forum_topic.topic_cat', '=', $cat)
			->count();
	}
	public function getNbTopicByCat($cat){
		return DB::table('forum_topic')
			->where('topic_cat', '=', $cat)
			->count();
	}
	/**
	// INSERTION SQL REQUESTS
	// function called by 'routes' which save the post into the database
	 */
	public function postMessage($cat,$topic){
		// Retreive data
		$inputData = Input::all();  // Get the data send in post
		$createurId = Auth::id();
		$post = $inputData['msg'];
		// SQL request to insert data into the database
		DB::table('forum_post')->insert([
			['post_createur' => $createurId, 'post_texte' => $post, 'post_time' => date('Y-m-d H:i:s') , 'post_topic_id' => $topic ]
		]);
	}
	public function supPost($cat,$topic){
		$inputData = Input::all();  // Get the data send in post
		$postId = $inputData['postId']; // Get the data send in post

		DB::table('forum_post')
			->where('post_id', '=', $postId)
			->update(['post_sup' => 1]);
	}
	public function supPostById(){
		$inputData = Input::all(); // Get the data send in post
		$idToSup = $inputData['idToSup'];

		return DB::table('forum_post')
			->where('post_createur', '=', $idToSup)
			->update(['post_sup' => 1]);
	}
	public function supPostByName(){
		$inputData = Input::all(); // Get the data send in post
		$nameToSup = $inputData['nameToSup'];
		$surnameToSup = $inputData['surnameToSup'];

		if(Auth::getIdByName($nameToSup,$surnameToSup) == null){
			return null;
		} else if(count(Auth::getIdByName($nameToSup,$surnameToSup)) == 1 ){
			if($this->__supPostById(Auth::getIdByName($nameToSup,$surnameToSup)[0]->id) == 1){
				return 1;
			}
		} else {
			return -1;
		}
	}
	public function supPostByPseudo(){
		$inputData = Input::all(); // Get the data send in post
		$pseudoToSup = $inputData['pseudoToSup'];

		if(Auth::getIdByPseudo($pseudoToSup)[0]->id == 1){
			return $this->__supPostById(Auth::getIdByPseudo($pseudoToSup)[0]->id);
		} else {
			return null;
		}
	}
	public function supPostByPostId(){
		$inputData = Input::all(); // Get the data send in post
		$postIdToSup = $inputData['postIdToSup'];

		return DB::table('forum_post')
			->where('post_id',$postIdToSup)
			->delete();
	}
	public function getPostById(){
		$inputData = Input::all(); // Get the data send in post
		$idToPrint = $inputData['idToPrint'];

		return json_encode(DB::table('forum_post')
			->where('post_createur', '=', $idToPrint)
			->get());
	}
	public function getPostByName(){
		$inputData = Input::all(); // Get the data send in post
		$nameToSup = $inputData['nameToSup'];
		$surnameToSup = $inputData['surnameToSup'];
		dd(Auth::getIdByName($nameToSup,$surnameToSup));

		if(Auth::getIdByName($nameToSup,$surnameToSup) == null){
			return null;
		} else if(count(Auth::getIdByName($nameToSup,$surnameToSup)) == 1 ){
			if($this->__getPostByCreatorId(Auth::getIdByName($nameToSup,$surnameToSup)[0]->id) == 1){
				return $this->__getPostByCreatorId(Auth::getIdByName($nameToSup,$surnameToSup)[0]->id) == 1;
			}
		} else {
			return -1;
		}
	}
	public function getPostByPseudo(){
		$inputData = Input::all(); // Get the data send in post
		$pseudoToPrint = $inputData['pseudoToPrint'];
		$idCreator = Auth::getIdByPseudo($pseudoToPrint)[0]->id;

		return DB::table('forum_post')
			->where('post_createur', '=', $idCreator)
			->get();
	}
	public function getPostByPostId(){
		$inputData = Input::all();
		return $this->__getPost($inputData['postIdToPrint']);
	}
	/*	TODO
        public function getPostByDate(){
             $inputData = Input::all();
            $pseudoToPrint = $inputData['pseudoToPrint'];
            $idCreator = Auth::getIdByPseudo($pseudoToPrint)[0]->id;

            return DB::table('forum_post')
                ->where('post_createur', '=', $idCreator)
                ->get();
        }
    */

	// Function called in post by 'routes' which modify a message into the database
	public function editPost($cat,$topicId,$postId){
		// Checks that user has the correct right pn the post
		$editeurId = Auth::id();
		$inputData = Input::all();  // Get the data send in post
		$postToReplace = $inputData['msgToSend'];
		if( Auth::isAdmin() ){
			return $this->__editPostById($postId,$postToReplace);
		} else if( $editeurId == $this->__getCreatorPostById($post_id)[0] ){ // getCreateurPostById retourne un tableau d'une case contenant l'id du createur du post
			return $this->__editPostById($postId,$postToReplace);
		} else {
			dd("Vous n'avez pas le droit d'accèder à cette page");
		}
	}
	// Function called by 'routes' which insert the new topic into the database
	public function createTopic($cat){
		$inputData = Input::all();  // Get the data send in post
		$creatorId = Auth::id();
		$messageTopic = $inputData['msgTopic'];
		$titleTopic = $inputData['titleTopic'];
		var_dump($messageTopic);
		var_dump($titleTopic);
		var_dump($creatorId);
		var_dump($cat);
		// Topic creation
		DB::table('forum_topic')->insert(
			['topic_titre' => $titleTopic, 'topic_createur' => $creatorId, 'topic_time' => date('Y-m-d H:i:s'), 'topic_cat' => $cat]
		);
		$postTopicId = $this->__getLastTopicId();
		// Post insertion
		DB::table('forum_post')->insert(
			['post_createur' => $creatorId, 'post_texte' => $messageTopic, 'post_topic_id' => $postTopicId->topic_id, 'post_time' => date('Y-m-d H:i:s')]
		);
	}
	public function viewTopic($cat,$topic){
		DB::table('forum_topic')
			->where('topic_id', $topic)
			->update(['topic_vu' => DB::table('forum_topic')
					->where('topic_id', $topic)
					->select('topic_vu')
					->get()[0]->topic_vu+1 ] );
	}

	/**
	DATABASE REQUESTS
	SELECTION SQL REQUESTS :

	Reminder : the prefix __function() means that function is private

	 */
	// Return the category's name taking it's id as a parameter
	private function __getCatName($cat){
		return DB::table('forum_categorie')
			->where('cat_id',$cat)
			->value('cat_nom');
	}
	// Return a table containing all posts of the topic in parameter
	private function __getPosts($topic){
		return DB::table('forum_post')
			->where('post_topic_id', $topic)
			->orderBy('post_time')
			->get();
	}

	private function __getPost($postId){
		return DB::table('forum_post')
			->where('post_id', $postId)
			->get();
	}

	private function __getForum(){
		return DB::table('forum_forum')
			->orderBy('forum_id')
			->get();

	}
	// Return all categories
	private function __getAllCategories(){
		return DB::table('forum_categorie')
			->orderBy('cat_ordre')
			->get();
	}
	// Return the topics which are in the category passed as a parameter
	private function __getTopicFromCat($cat){
		return DB::table('forum_topic')
			->where('topic_cat', $cat)
			->orderBy('topic_id')
			->get();
	}
	// Return the topics which are in the category passed as a parameter
	// This function start with topic firstTopicToReturn and return 15 topics max
	private function __getTopicFromCatLimit($cat,$firstTopicToReturn){
		return DB::table('forum_topic')
			->where('topic_cat', $cat)
			->orderBy('topic_id')
			->skip($firstTopicToReturn)
			->take(15)
			->get();
	}
	// Return all topics
	private function __getAllTopics(){
		return DB::table('forum_topic')
			->get();
	}
	// Return the topic given a a parameter
	private function __getTopic($topicId){
		return DB::table('forum_topic')
			->where('topic_id', $topicId)
			->get();
	}
	private function __getCatByPost($postId){
		return DB::table('forum_post')
			->join('forum_topic', 'forum_post.post_topic_id', '=', 'forum_topic.topic_id')
			->join('forum_categorie', 'forum_topic.topic_cat', '=', 'forum_categorie.cat_id')
			->where('post_id', $postId)
			->where('post_sup', 0)
			->get();
	}
	private function __getCatByTopic($topicId){
		return DB::table('forum_topic')
			->where('topic_id', $topicId)
			->value('topic_cat');
	}
	private function __getCreatorPostById($post_id){
		return DB::table('forum_post')
			->where('post_id', '=', $post_id)
			->select('post_createur')
			->get();
	}
	private function __getPostMessageById($post_id){
		return DB::table('forum_post')
			->where('post_id', '=', $post_id)
			->select('post_texte')
			->get();
	}
	private function __getLastTopicId(){
		return DB::table('forum_topic')
			->select('topic_id')
			->orderBy('topic_id', 'desc')
			->first();
	}

	private function __getLastTopicIdByCat($cat){
		return DB::table('forum_topic')
			->select('topic_id')
			->where('topic_cat', $cat)
			->orderBy('topic_id', 'desc')
			->first();
	}
	private function __getLastPostId($topicId){
		return DB::table('forum_post')
			->select('topic_id')
			->where('post_topic_id', '=', $topicId)
			->max('post_id');
	}
	private function __getLastPostCreator($topicId){
		return DB::table('forum_post')
			->select('post_createur')
			->where('post_topic_id', '=', $topicId)
			->orderBy('post_id', 'desc')
			->first();
	}
	// Return the last post using categorie id
	private function __getLastPostByCat($cat){
		return DB::table('forum_post')
			->join('forum_topic', 'forum_post.post_topic_id', '=', 'forum_topic.topic_id')
			->join('forum_categorie', 'forum_topic.topic_cat', '=', 'forum_categorie.cat_id')
			->where('forum_topic.topic_cat', $cat)
			->orderBy('post_id', 'desc')
			->first();
	}
	// Return the last post creator's id of a certain category
	private function __getLastPostCreatorIdByCat(){
		return DB::table('forum_post')
			->join('forum_topic', 'forum_post.post_topic_id', '=', 'forum_topic.topic_id')
			->join('forum_categorie', 'forum_topic.topic_cat', '=', 'forum_categorie.cat_id')
			->select('post_createur')
			->orderBy('post_id', 'desc')
			->first();
	}
	// Return the last post date of a certain category
	private function __getLastPostDateByTopic($topicId){
		return DB::table('forum_post')
			->select('post_time')
			->where('post_topic_id', $topicId)
			->orderBy('post_id', 'desc')
			->first();
	}
	// Return the number of topic which are in a category
	private function __getNbTopic($cat){

		return DB::table('forum_topic')
			->where('topic_cat', '=', $cat)
			->count();
	}
	private function __getPostByCreatorId($id){
		return DB::table('forum_post')
			->where('post_createur', $id)
			->where('post_sup', 0)
			->get();
	}
	private function __getNbPostByTopic($topic_id){
		return DB::table('forum_post')
			->where('post_topic_id', '=', $topic_id)
			->where('post_sup', '=', 0) // if the topic hasn't been deleted
			->distinct()
			->count();
	}
	private function __getNbPostByCreatorId($creatorId){
		return DB::table('forum_post')
			->where('post_createur', '=', $creatorId)
			->where('post_sup', '=', 0) // if the topic hasn't been deleted
			->count();
	}
	private function __getNbTopicByCreatorId($creatorId){
		return DB::table('forum_topic')
			->where('topic_createur', '=', $creatorId)
			->count();
	}
	private function __supPostById($idToSup){
		return DB::table('forum_post')
			->where('post_createur', '=', $idToSup)
			->update(['post_sup' => 1]);
	}
	private function __editPostById($idToSup,$postToReplace){
		return DB::table('forum_post')
			->where('post_id', '=', $idToSup)
			->update(['post_edit' => 1, 'post_texte' => $postToReplace, 'post_edit_time' => date('Y-m-d H:i:s')]);
	}

	public function test(){
		dd($this->__getNbPostByCreatorId(4));
	}
}
?>