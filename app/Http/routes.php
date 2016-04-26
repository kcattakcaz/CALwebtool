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
    return redirect(action('HomeController@index'));
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
    Route::get('form/{form}/judges','FormDefinitionController@judges');
    Route::post('form/{form}/judges','FormDefinitionController@updateJudges');
    Route::resource('form','FormDefinitionController');

    Route::get('submissions/form/{form}','SubmissionController@getForm');
    Route::get('submissions/unscored/{form}','SubmissionController@unscored');
    Route::get('submissions/scored/{form}','SubmissionController@scored');
    Route::get('submissions/completed/{form}','SubmissionController@completed');
    Route::get('submissions/{submissions}/trash','SubmissionController@trash');
    Route::resource('submissions','SubmissionController');

    Route::get('moderation/approve/{submissions}','SubmissionController@accept');
    Route::post('moderation/approve/{submissions}','SubmissionController@approve');
    Route::get('moderation/reject/{submissions}','SubmissionController@reject');
    Route::get('moderation/moderate/{submissions}','SubmissionController@moderate');
    Route::get('moderation/unlock/{submissions}','SubmissionController@unlock');
    Route::get('moderation/rejectNotify/{submissions}','SubmissionController@rejectNotify');
    Route::post('moderation/sendRejectNotify/{submissions}','SubmissionController@sendRejectNotify');

    Route::get('public/forms/{formDef}','FormDefinitionController@displayForm');
    Route::post('public/forms/{formDef}','SubmissionController@store');
    
    //Scores
    Route::resource('submissions.scores','ScoreController');


    //Testing

    //Route::get('schedform','FormDefinitionController@scheduleForms');
    //Route::get('scoreupdate','ScoreController@autoSubmissionStatus');

});
