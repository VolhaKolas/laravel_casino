<?php


Route::get('/', ['uses'=>'HomeController@home', 'as' => 'home']);
Route::get('/rules', ['uses'=>"RulesController@rules", 'as'=>'rules']);

Route::get('/texas', ['uses'=>"TexasHoldemController@game", 'as'=>'texas']);


Route::group(['middleware' => ['auth']], function($router) {

    $router->get('/userpage', ['uses'=>"UserPageController@userpage", 'as'=>'userpage']);
    $router->post('/online', ['uses'=>"OnlineController@online", 'as'=>'online']); //ajax determine user is online or not

    $router->get('/texas', ['uses'=>"TexasHoldemController@game", 'as'=>'texas'])->middleware('texas');
});

Auth::routes();
