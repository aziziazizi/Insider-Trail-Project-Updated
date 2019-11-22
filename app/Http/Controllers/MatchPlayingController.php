<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\League;
use App\Team;
use App\MatchTeamPlayer;
use App\MatchTeamPlayerGoal;
use Faker\Factory as Faker;


class MatchPlayingController extends Controller
{
	
	
	public function play($round , $league_id)
    { 
		
		$league = League::find(decrypt($league_id));
		
		// get the matches that should start playing
		$matches_to_be_played = $league->mathes_for_round(decrypt($round))->get();
		
		// class for faker data
        $faker = Faker::create();
		
		// loop through playing match for inserting some players
		foreach($matches_to_be_played as $round_wise => $round_match){
			
			$team_results_array = array();
			
			$get_first_team_runs_count = -1;
			
			$first_match_team_object = null;
			
			
			//get two teams that plays with each other
			foreach($round_match->match_teams as  $match_team)
			{	
				// get each team players like each club has some players	
				$team_players = $match_team->team->players->toArray();	
				
				// array which stores just the idies for randomelements
				$players_array = array();					
				foreach($team_players  as $player)
				{
					$players_array [] = $player['id'];
				}
								
				$match_teams_selected_players = array();
				// each team has 11 player in a match so this loop select those players and inserts into the playing match
				
				for ($i = 1; $i <= 11; $i++)
				{	
					$player_array = [
						'match_team_id' => $match_team->id, 						 
						'player_id' => count($players_array) >=  11 ? $faker->randomElement($players_array) :  $faker->randomElement($players_array)];
					
					//batting order creation	
					$match_teams_selected_players [] = $match_team->players()->create($player_array);	
					
					//bowling order creation
					$match_teams_selected_players [] = $match_team->bowling_players()->create($player_array);					
				}				
				
				$match_team->started = 'yes';
				$match_team->start_time = date('H:i:s');//match should start now
				$match_team->save();
			}
			
		}
		
		$round = decrypt($round);
		return Redirect()->route('view_leagues_matches',[$league_id,($round!='all' ? ++$round : $round)]);
			      
    }
	
	
   
}





?>