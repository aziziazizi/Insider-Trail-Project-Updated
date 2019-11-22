<?php

namespace App;
use Carbon\Carbon;
use App\Team;
use App\Match;
use App\MatchTeam;
use Illuminate\Database\Eloquent\Model;
use DB;

class League extends Model
{   
    /**
     required 
     */
    protected $fillable = [
        'name','no_overs'
    ];
	
	/**
     * adding custom attributes
	 * @var array
     */ 
	protected $append = ['teamscount','teams','max_round'];
	
	/**
     * MySql Type Casting
	 * @var array
     */
	
    protected $casts = [        
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    /**
     * Get the teams count for each League.
     * 
     * @return integer
     */
	 
	public function getTeamsCountAttribute()
	{      
	   $all_matches_with_teams =  $this->matches()->with('teams');	  
	   $distinct_teams_array = array();
	   $all_matches_with_teams->each(function($match) use (&$distinct_teams_array)
	   {	   		   
		   $teams = $match->teams;
		   $teams->each(function($team) use (&$distinct_teams_array){			  
			  $distinct_teams_array [$team->id ] = true;			  
		   });
	   });	   
	   return count($distinct_teams_array);
	}
	
	/**
     * Get the teams for each League.
     * 
     * @return array
     */
	public function getTeamsAttribute()
	{      
	   $all_matches_with_teams =  $this->matches()->with('teams');	  
	   $distinct_teams_array = array();
	   $all_matches_with_teams->each(function($match) use (&$distinct_teams_array)
	   {	   		   
		   $teams = $match->teams;
		   $teams->each(function($team) use (&$distinct_teams_array){
			  if(!isset($distinct_teams_array [$team->id]))
			  $distinct_teams_array [$team->id] = $team;			  
		   });
	   });	   
	   return $distinct_teams_array;
	}
	
	/**
     * Get the maximumu amount of round which can be used for pagination purpose
     * 
     * @return integer
     */
	public function getMaxRoundAttribute()
	{      
	  return $this->matches()->select("round")->where("round",DB::raw("(select max(round) from matches)"))->limit(1)->first()->round;	 
	}	

    /**
     * Get the realted matches of each League.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches()
    {
        return $this->hasMany(Match::class);
    }
	
    /**
     * Get each played match of the specific round.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function played_matches_for_round($round = 'all')
    {
        return $round === 'all' 
                    ? 
                $this->matches()->where('status', 'played')->with('match_teams','match_teams.all_runs') 
                    : 
                $this->matches()->where('status', 'played')->where('round', $round)->with('match_teams','match_teams.all_runs','match_teams.team.runs.match.player');
    }
	
	 /**
     * Get each not played match of the specific round.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function not_played_matches()
    {
        return $this->matches()->where('status', 'pending');                 
    }
    
	/**
     * Get every match of the specific round.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function mathes_for_round($round = 'all')
    {   
        return $round === 'all' 
                    ?
				$this->matches()->with('match_teams_not_started')              
					:
               $this->matches()->where("round",$round)->with('match_teams_not_started');            
    }
		
    /**
     * Get each not played match of the specific round.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function not_played_matches_for_round($round = 'all')
    {	
        return $round === 'all' 
                    ? 
                $this->matches()->where('status', 'pending')->where('round', $round)->with('match_teams_not_started')
                    : 
                $this->matches()->where('status', 'pending')->where('round', $round)->with('match_teams_not_started');
    }
		
    /**
     * Get each started match of the specific round.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function started_matches($round = 'all')
    {	
        return $round === 'all' 
                    ? 
                $this->matches()->where('status', 'pending')->where('round', $round)->with('match_teams_started')
                    : 
                $this->matches()->where('status', 'pending')->where('round', $round)->with('match_teams_started');
    }
	
}