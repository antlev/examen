<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => 'web'], function () {
	Route::get('register','HomeController@Register');

	Route::auth();
	


	Route::post('password/reset', 'Auth\PasswordController@postReset');
	Route::get('user', 'alexController@getInfos');
	Route::get('get_formulaire', 'alexController@get_formulaire');
	Route::post('users', 'alexController@postInfos');
	Route::get('/home', 'HomeController@index');
	Route::get('/', 'HomeController@index');
	Route::get('calendar','alexController@getCalendar');
	Route::get('calendars','alexController@postCalendar');
	Route::get('add_event','alexController@add_event');
	Route::get('lieu', 'alexController@autocomplete');
	Route::get('list_view/{table}', 'alexController@index');
	Route::get('update', 'alexController@update');
//Quiz
    Route::get('/quizz/reponse','quizzController@quizzReponse');
	Route::get('quizz','quizzController@index');
	Route::get('quizz/game','quizzController@gamepage');

	Route::post('insertquestions','quizzController@insertQuestions');
    Route::post('quizz/getgame','quizzController@getgame');
	Route::post('quizz/question','quizzController@getQuestions');
	Route::post('quizz/checkanswer','quizzController@checkAnswer');
	Route::post('quizz/questionreponse','quizzController@QuestionReponse');
//profil
	Route::post('auth/{id}', 'Auth\AuthController@getUserName');
    Route::post('changeUserInfo','HomeController@changeUserInfo');
    Route::post('postimage','HomeController@postImage');
	Route::post('msg', 'HomeController@sbInsert');
	Route::post('sendprivmsg','HomeController@sendPrivMsg');
	Route::post('getprivmsg','HomeController@getPrivMsg');
    Route::post('readmsg','HomeController@readMsg');
	Route::post('shouts', 'HomeController@getShouts');
    Route::get('admin_profil','HomeController@getProfil');
	Route::get('profil','HomeController@userProfil');
	Route::get('members','HomeController@Members');
	Route::get('members/{id}','HomeController@MembersById');

	Route::get('supp_view', 'alexController@supp_view');
	Route::get('create', 'alexController@postIndex');
	// Route::get('suivi_mot_passe', 'alexController@suivi_mot_passe');
	// Route::get('suivi_reserve', 'alexController@suivi_reserve');


//Forum
	Route::get('/forum/test', 'forumController@test');
	// Index
	Route::get('/forum/', 'forumController@index');
	// Admin
	// The following line MUST BE PLACED BEFORE the line 'Route::get('/forum/{cat}', 'forumController@cat');');'
	Route::get('/forum/admin','forumController@adminView');
	// Suppression methods
	Route::post('/forum/admin/supById','forumController@supPostById');
	Route::post('/forum/admin/supByName','forumController@supPostByName');
	Route::post('/forum/admin/supByPseudo','forumController@supPostByPseudo');
	Route::post('/forum/admin/supByPostId','forumController@supPostByPostId');
	Route::post('/forum/admin/supByPostDate','forumController@supPostByDate');
	// Print methods
	Route::post('/forum/admin/printById','forumController@getPostById');
	Route::post('/forum/admin/printByName','forumController@getPostByName');
	Route::post('/forum/admin/printByPseudo','forumController@getPostByPseudo');
	Route::post('/forum/admin/printByPostId','forumController@getPostByPostId');
	Route::post('/forum/admin/printByPostDate','forumController@getPostByPostDate');
	Route::post('/forum/getPostInfoById','forumController@getPostInfoById');
	// Profil
	Route::get('/forum/{auth}/myPosts', 'forumController@myPosts');
	Route::get('/forum/{auth}/myProfil', 'forumController@myProfil');
	Route::post('/forum/{cat}/next', 'forumController@nextCat');
	// The following line MUST BE PLACED BEFORE the line 'Route::get('/forum/{cat}/{topic}', 'forumController@topic');'
	Route::get('/forum/{cat}/newTopic', 'forumController@newTopic');
	// The following line MUST BE PLACED BEFORE the line 'Route::get('/forum/{cat}/{topic}', 'forumController@topic');'
	Route::post('/forum/{cat}/saveMsgTopic','forumController@createTopic');
	Route::post('/forum/{cat}/{topic}/savePost','forumController@postMessage');
	Route::post('/forum/{cat}/{topic}/supPost','forumController@supPost');
	Route::get('/forum/{cat}/{topic}/newPost','forumController@newPost');
	Route::post('/forum/{cat}/{topic}/viewTopic','forumController@viewTopic');
	Route::post('/forum/{cat}/{topic}/{post_id}/editPost','forumController@editPost');
	Route::get('/forum/{cat}/{topic}/{post_id}/editPost','forumController@editPostView');
	Route::get('/forum/{cat}/{topic}', 'forumController@topic');
	Route::get('/forum/{cat}', 'forumController@cat');

});
