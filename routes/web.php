<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::group(['middleware' => ['auth']], function($router) {
    $router->get("/edit", "EditController@get");
    $router->post("/edit", "EditController@post")->name('edit');

    $router->get("/editpass", "EditPassController@get");
    $router->post("/editpass", "EditPassController@post")->name('editpass');

    $router->get('/play', 'PlayController@get');
    $router->post('/play', 'PlayController@post')->name('play');

    $router->post('/break', 'BreakController@post')->name('break');
    $router->post('/admission', 'AdmissionController@post')->name('admission');

    $router->post('/answer', 'AnswerController@post')->name('answer');
    $router->post('/socket', 'AnswerController@socket')->name('socket');
    $router->post("/invitation", 'AnswerController@invitation')->name('invitation');
    $router->post("/setinput", 'AnswerController@setinput')->name('setinput');

    $router->post("/continue", 'ContinueController@post')->name('continue');
    $router->post("/exit", 'ExitController@post')->name('exit');
    $router->post("/bet", 'BetController@post')->name('bet');
    $router->post("/next", 'NextController@post')->name('next');
});



Route::get('/', 'WelcomController@index');
Route::get('/home', 'HomeController@index')->name('home');