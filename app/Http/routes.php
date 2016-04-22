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

Route::get('/', function () {
    return view('welcome');
});

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
/*
Route::group(['middleware' => ['web']], function () {
    //
}); */

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');

    Route::get('/settings','HomeController@settings');
    Route::get('/unavailable','HomeController@unavailable');

    //group
    Route::delete('settings/group/{group}/user/{user}','GroupController@removeUser');
    Route::resource('settings/group','GroupController');

    //user
    Route::get('settings/user/deactivated','UserController@deactivatedIndex');
    Route::get('settings/user/forceDelete','UserController@forceDelete');
    Route::get('users/reactivate','UserController@reactivate');
    Route::resource('settings/user','UserController');
    Route::post('users/activate','UserController@activate');
    Route::get('users/activate','UserController@register');


    //forms / submissions
    Route::get('form/{form}/schedule','FormDefinitionController@schedule');
    Route::post('form/{form}/schedule','FormDefinitionController@updateSchedule');
    Route::post('form/{form}/status','FormDefinitionController@updateStatus');
    Route::get('form/confirmDelete','FormDefinitionController@delete');
    Route::resource('form','FormDefinitionController');
    Route::get('submissions/form/{form}','SubmissionController@getForm');
    Route::resource('submissions','SubmissionController');

    Route::get('moderation/reject/{submissions}','SubmissionController@reject');
    Route::get('moderation/moderate/{submissions}','SubmissionController@moderate');
    Route::get('moderation/unlock/{submissions}','SubmissionController@unlock');
    Route::get('moderation/reject/{submissions}','SubmissionController@reject');

    Route::get('public/forms/{formDef}','FormDefinitionController@displayForm');
    Route::post('public/forms/{formDef}','SubmissionController@store');
    
    //Scores
    Route::resource('submissions.scores','ScoreController');

});

Route::group([],function(){

});
