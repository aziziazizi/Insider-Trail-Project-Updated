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


// when app loads
Route::get('/',
	[
		'uses' => 'LeagueController@index',
		'as' => 'view_leagues'		
	]);

// create new league
Route::get('/store/league',
	[
		'uses' => 'LeagueController@store_league',
		'as' => 'store_league'		
	]);
	
//view leagues matches	
Route::get('/view/league/matches/{id}/{round?}',
	[
		'uses' => 'LeagueMatchesController@show',
		'as' => 'view_leagues_matches'		
	]);	

	
//view leagues matches	
Route::get('/play/round/matches/{round}/{league_id}',
	[
		'uses' => 'MatchPlayingController@play',
		'as' => 'play_round_matches'		
	]);	









