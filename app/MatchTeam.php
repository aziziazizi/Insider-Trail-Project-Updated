<?php

namespace App;
use App\Team;
use App\League;
use App\MatchTeamPlayerBatsMan;
use App\MatchTeamPlayerBowler;
use App\MatchTeamPlayerRun;
use Illuminate\Database\Eloquent\Model;

class MatchTeam extends Model
{   

   /**
     * required
     */
	 
    protected $fillable = [
        'team_type', 'stadium','toss','elected_to','runs','played_balls','run_rate','wicket_lost','total_balls', 'team_id', 'match_id' 
    ];   

	/**
     * adding custom attributes
	 * @var array
     */ 
	 
	protected $append = ['received_runs'];
	
	
   	/**
     * MySql Type Casting
	 * @var array
     */
	 
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];
   
    /**
     * Get the team for the playing match.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	 
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
		
	/**
     * Get the players for the playing match.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
	
    public function players()
    {
        return $this->hasMany(MatchTeamPlayerBatsMan::class);
    }
	
	/**
     * Get the bowling players for the playing match.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
	
    public function bowling_players()
    {
        return $this->hasMany(MatchTeamPlayerBowler::class)->with("player")->orderBy("balls_played","desc");
    }
		
	/**
     * Get the Batsman players for the playing match.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
	
    public function batting_players()
    {
        return $this->hasMany(MatchTeamPlayerBatsMan::class)->with("player");		
    }
	
	/**
     * Get the match with the second team.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
	
	public function match()
    {
        return $this->belongsTo(Match::class);
    }	
	
	/**    
     * Scope a query to get the opposite team for a team in a playing match.
     *
     *@param integer $team_id
     *@return \Illuminate\Database\Eloquent\Builder
     */
    
	public function opposite($team_id)
    {
        return $this->where("team_id", "!=", $team_id)->where("match_id", $this->match_id);
    }
	
	/**
     * Get the match with the second team.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
    */
	
	public function all_runs()
    {   
      return $this->hasManyThrough(MatchTeamPlayerRun::class,MatchTeamPlayerBatsMan::class,'match_team_id','match_team_player_batsman_id');
    }
	
	/**
     * Get the recieved runs count for the opposite team.
     * 
     * @return integer
     */
	 
    public function getReceivedRunsAttribute()
    {   		
		$received_runs = 0;	
		$match_teams = $this->match();		
		$match_teams->each(function($match_team) use (&$received_runs )
	    {	
			$get_opposite_team = $match_team->opposite($this->team_id)->first();		
			$received_runs += $get_opposite_team->runs;		   
	    });		
		return $received_runs;		
    }
}
