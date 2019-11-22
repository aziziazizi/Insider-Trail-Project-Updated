<?php

namespace App;
use App\MatchesGoal;
use App\Match;
use App\Player;
use App\League;
use Illuminate\Database\Eloquent\Model;


class Team extends Model
{   
   
    /**
     * required
     */
	 
    protected $fillable = [
        'name', 'flag'
    ];

	/**
     * adding custom attributes
	 * @var array
     */ 
	 
	protected $append = ['total_runs','played_overs','received_runs','played_overs','net_run_rate','received_overs'];
	
	/**
     * MySql Type Casting
	 * @var array
     */
	 
    protected $casts = [
        'name' => 'string',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];
   

    /**
     * Get won matches.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	 
    public function won_matches()
    {
        return $this->hasMany(MatchTeam::class)->where("result",'won');
    }

    /**
     * Get the drawn matches.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	 
    public function drawn_matches()
    {
        return $this->hasMany(MatchTeam::class)->where("result",'drawn');
    }
	
    /**
     * Get the lost matches.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	 
    public function lost_matches()
    {
        return $this->hasMany(MatchTeam::class)->where("result",'lost');
    }   
	
	/**
     * Get the played matches.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	 
    public function played_matches()
    {   
        return $this->BelongsToMany(Match::class,'match_teams')->withPivot(['status','runs'])->where('status','!=','pending');
    }
	
	/**
     * Get the five last played matches.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	 
    public function last_five_played_matches()
    {   
        return $this->BelongsToMany(Match::class,'match_teams')->withPivot(['runs'])->where('status','!=','pending')->orderBy("id","desc")->limit(5);
    }
	
	 /**
     * Get the Team's playing matches .
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */ 
	 
	public function matches()
    {
        return $this->hasMany(MatchTeam::class);
    }
		
	/**
     * Get the recieved runs count in all matches.
     * 
     * @return integer
     */
	 
    public function getReceivedRunsAttribute()
    {   		
		$received_runs = 0;	
		$match_teams = $this->matches();		
		$match_teams->each(function($match_team) use (&$received_runs )
	    {	
			$get_opposite_team = $match_team->opposite($this->id)->first();		
			$received_runs += $get_opposite_team->runs;		   
	    });		
		return $received_runs;		
    }
	
	/**
     * Get the recieved overs count in all matches.
     * 
     * @return integer
     */
	 
    public function getReceivedOversAttribute()
    {   		
		$received_runs = 0;	
		$match_teams = $this->matches();		
		$match_teams->each(function($match_team) use (&$received_runs )
	    {	
			$get_opposite_team = $match_team->opposite($this->id)->first();		
			$received_runs += $get_opposite_team->played_balls;		   
	    });		
		return round($received_runs/6,2);		
    }
	
	
	/**
     * Get the played overs count in all matches.
     * 
     * @return integer
     */
	 
    public function getPlayedOversAttribute()
    {   
		return round(($this->matches()->sum("played_balls")/6),2);		
    }
	
    /**
     * Get the hit run count in all matches.
     * 
     * @return integer
     */
	 
    public function getTotalRunsAttribute()
    {  
		return $this->matches()->sum("runs");		
    }
	
	 /**
     * Get the net run rate count in all matches.
     * 
     * @return integer
     */
	 
    public function getNetRunRateAttribute()
    {  
		// opposite teams played overs
		$received_overs = 1;	
		$match_teams = $this->matches();		
		$match_teams->each(function($match_team) use (&$received_runs )
	    {	
			$get_opposite_team = $match_team->opposite($this->id)->first();		
			$received_runs += $get_opposite_team->played_balls;		   
	    });	
		
		// opposite teams runs
		$received_runs = 1;	
		$match_teams = $this->matches();		
		$match_teams->each(function($match_team) use (&$received_runs )
	    {	
			$get_opposite_team = $match_team->opposite($this->id)->first();		
			$received_runs += $get_opposite_team->runs;		   
	    });	
		
		// opposite teams played overs
		$hit_runs = $this->matches()->sum("runs")==0?1:$this->matches()->sum("runs");
		
		// this teams runs
		$hit_overs = round(( ($this->matches()->sum("played_balls")==0?  6 : $this->matches()->sum("played_balls")) / 6), 2);	
		
		return ($hit_runs / $hit_overs)- ($received_runs / $received_overs) ;		
    }
		
	 /**
     * Get the runs for the specific team.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	
	public function runs()
    {   
      return $this->hasManyThrough(MatchTeamPlayerRun::class,MatchTeamPlayerBatsMan::class,'match_team_id','match_team_player_bats_man_id');
    }
	
	 /**
     * Get the bowls for the specific team.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	
	public function bowls()
    {   
      return $this->hasManyThrough(MatchTeamPlayerRun::class,MatchTeamPlayerBowler::class,'match_team_id','match_team_player_bowler_id');
    }
	
	 /**
     * Get players related to the team.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
	 
    public function players()
    {
        return $this->hasMany(Player::class);
    }	
}