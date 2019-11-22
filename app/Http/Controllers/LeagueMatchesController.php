<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\League;
use App\Team;
use App\MatchTeamPlayer;
use App\MatchTeamPlayerGoal;
use Faker\Factory as Faker;

class LeagueMatchesController extends Controller
{
    public function show($league_id , $round = 1)
    { 		
		
		$league = League::find(decrypt($league_id));		
		
		$is_league_ended =  $league->not_played_matches->count() == 0 ? true : false;
		
		// round maximum then the total rounds of matches then maximum round is returned
		if( $round != 'all' && $round > $league->max_round  && !$is_league_ended)
			$round = $league->max_round;
		
		//get all teams in league
		$legue_teams = $league->teams;	
		
		//get all the teams of the league but with matches played and are beig sorted
		$teams = Team::withCount('played_matches','last_five_played_matches','won_matches','drawn_matches','lost_matches')
		->whereIn('id',array_keys($legue_teams))->orderBy("won_matches_count","desc")->orderBy("drawn_matches_count","desc")->get();
		
		
		//get all played matches 
		$matches = $league->played_matches_for_round($round != 'all' ? $round-1 : $round)->get();//get previous round match of the round but not for one
		
		//get  not played matches for playing for round to start
		$not_started_matches = $league->not_played_matches_for_round($round != 'all' ? $round : $round)->get();//get previous round match of the round
		
		//get all started matches for playing for round
		$started_matches = $league->started_matches($round != 'all' ? ($league->max_round == 1 ? $round : $round-1) : $round)->get();//get previous round match of the round
		
		
		
		
		return view('actions.round_matches')->with(array(
			'teams' => $teams,'matches' => $matches,
			'not_started_matches' => $not_started_matches, 
			'started_matches' => $started_matches, 
			'round' => encrypt($round), 'league_id' => $league_id, 
			'league' => $league,'is_league_ended' => $is_league_ended));		      
    }
}