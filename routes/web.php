<?php


Route::get('/', ['uses'=>'HomeController@home', 'as' => 'home']);
Route::get('/rules', ['uses'=>"RulesController@rules", 'as'=>'rules']);

Route::get('/texas', ['uses'=>"TexasHoldemController@game", 'as'=>'texas']);

Route::group(['middleware' => ['auth']], function($router) {
    $router->get('/texas', ['uses'=>"TexasHoldemController@game", 'as'=>'texas']);
});

Auth::routes();