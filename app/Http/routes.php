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

    Route::resource('settings/group','GroupController');
    //Route::('settings/group/user', 'GroupController@editGroupUser');
    Route::delete('settings/group/user','GroupController@removeUser');

    Route::resource('settings/user','UserController');

    Route::resource('form','FormDefinitionController');
});
