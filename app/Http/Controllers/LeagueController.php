<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\League;
use App\Team;
use App\Athelit;
use App\Player;
use App\Match;
use App\MatchTeam;
use Faker\Factory as Faker;

class LeagueController extends Controller
{
	
	public function index(Request $request)
    {   					
		$leagues = League::withCount("matches")->orderBy("id","desc")->paginate(10);		
		return view('index')->with('leagues',$leagues);        
    }

    public function store_league(Request $request)
    {			
		// no of teams that should be in this league
		$no_of_teams = $request->no_of_teams;
		
		// no of overs that each team should play
		$no_of_overs = $request->no_of_overs;
		
		//create league object	
		$league_object = factory(League::class,1)->create(['name' => $request->name, 'no_of_overs' => &$no_of_overs])->each(function ($league) use ($no_of_teams, $no_of_overs) {				
		
		//create teams with some players				
		$teams_array  = factory(Team::class, (integer)$no_of_teams)->create()->each(function($team) {
			
			// insert some players that a team should have	
			$team->players()->saveMany(factory(Player::class,25)->make());
			
		})->toArray();
		
		//generates all the required match table for each round with matches that each team should play with
		$weeks_matches = $this->generate_weeks_matches($teams_array);
		
		//class for some random data
		$faker = Faker::create();
		
		//loop through each round
		foreach($weeks_matches as $round => $week_match_teams)
		{		
			//inserts match table that each team should play with
			foreach($week_match_teams as  $teams){
				
				//create match with the total balls that each team should play
				$match = $league->matches()->create(['round' => $round + 1, 'total_balls' => ( (integer)$no_of_overs * 6) ]);
				
				// toss generation
				$fist_team_toss = $faker->randomElement(array('won', 'lost'));
				$second_team_toss = $fist_team_toss == 'won' ? 'lost' : 'won';
				
				// winner team bat or bowl selection and second team should be opposite
				$selection_of_first = $faker->randomElement(array('bat', 'not_bat'));
				$selection_of_second = $selection_of_first == 'bat' ? 'not_bat' : 'bat';
				
				// inserts each match teams for playing in a match
				foreach($teams as  $team_type => $team ){				
					
					// create team that should play in a match	
					$match->match_teams()->create([
					'toss' => ($fist_team_toss != '' ? $fist_team_toss : $second_team_toss),
					'elected_to' =>($selection_of_first != '' ? $selection_of_first : $selection_of_second) ,
					'stadium' => $faker->city,					
					'team_type' => $team_type , 'team_id' => $team['id']]);
					
					// makes empty because the second team should be opposite of first team
					$fist_team_toss = '';
					$selection_of_first = '';
					
				}	
			}			
		}				
        })->toArray();
		
		
		$league = League::withCount('matches')->find($league_object[0]['id']);		
		// return the new created league
		return \Response::json(array('msg' => 'Successfully created !!!!!', 'id' => encrypt($league_object[0]['id']), 'teamscount' => $league->teamscount, 'league' => $league));
   }
	
	
    /**
     * Weeks matches array generation
     *
     * @param array $teams
     * @return array
     */
    public function generate_weeks_matches(array $teams)
    {
		//gets first part of the two parts actually removes other items after half index from the teams array
        $guests = array_splice($teams, (count($teams) / 2)); 
		
		//gets the remaining items			
        $hosts = $teams;	
					
		//week wise teams matches
        $week_wise_participent_teams = [];	
				
		// loop through the number of weeks the teams should play in		
        for ($i=0; $i < count($hosts) + count($guests)-1 ; $i++)	
		{ 
			
			// each week each team can play one match against another
            for ($j=0; $j < count($hosts); $j++)	
			{ 				
                $week_wise_participent_teams[$i][$j]['host'] = $hosts[$j];	
                $week_wise_participent_teams[$i][$j]['guest'] = $guests[$j];
            }  
			
			// if teams greater than 2 then we changes the teams position next week matches
			if (count($hosts) + count($guests)-1 > 2)	 
			{	
				// removes second team from hosts		
                $x = array_splice($hosts, 1, 1);	
				
				// adds the second team ($x) to the guests teams to numerical zero index
                array_unshift($guests, array_shift($x));
				
				// adds the guests second team to the end of hosts teams and also removes from guest
                array_push($hosts, array_pop($guests));	
            }
        }		
        return $week_wise_participent_teams;
    }
	
}





?>