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

    Route::get('/preplay', 'PrePlayController@get');
    Route::post('/preplay', 'PrePlayController@post')->name('preplay');


    Route::get('/play', 'PlayController@get');
    Route::post('/play', 'PlayController@post')->name('play');


    Route::post('/break', 'BreakController@post')->name('break');
});

Route::get('/', 'WelcomController@index');
Route::get('/home', 'HomeController@index')->name('home');