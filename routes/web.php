<?php


Route::get('/', ['uses'=>'HomeController@home', 'as' => 'home']);
Route::get('/rules', ['uses'=>"RulesController@rules", 'as'=>'rules']);


Route::group(['middleware' => ['auth']], function($router) {

    $router->get('/userpage', ['uses'=>"UserPageController@userpage", 'as'=>'userpage']);
    $router->post('/online', ['uses'=>"OnlineController@online", 'as'=>'online']); //ajax determine user is online or not

    $router->post('/pregame', ['uses'=>"PreGameController@pregame", 'as'=>'pregame']);
    $router->get('/before', ['uses'=>"PreGameController@before", 'as'=>'before']);
    $router->get('/deleteUser', ['uses'=>"PreGameController@deleteUser", 'as'=>'deleteUser']);

    $router->get('/texas', ['uses' => 'TexasHoldemController@game', 'as' => 'texas'])->middleware('texas');
});

Auth::routes();
