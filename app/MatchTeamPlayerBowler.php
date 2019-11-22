<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Player;
use  App\Team;
use  App\MatchTeamPlayerRun;
use  App\MatchTeam;

class MatchTeamPlayerBowler extends Model
{
     /**
     * required
     * @var array
     */
	 
    protected $fillable = [
        'match_team_id','player_id',
    ]; 

	/**
     * MySql Type Casting
	 * @var array
     */
	 
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    /**
     * Get the playing match for the specific player.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	 
    public function team()
    {
        return $this->belongsTo(MatchTeam::class);
    }	
	
	/**
     * Get the player details for.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	 
	public function player()
    {
        return $this->belongsTo(Player::class);
    }
	
	/**
     * Get the player for the specific match.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	 
	public function wickets()
    {
        return $this->hasMany(MatchTeamPlayerRun::class,'match_team_player_bowler_id')->where('status','lbw','bold','stamp','catch');
    }
		
	/**
     * Scope a query to only include four runs .
     *
	 *@return \Illuminate\Database\Eloquent\Relations\HasMany
     *@return \Illuminate\Database\Eloquent\Builder    
     */
	 
	public function fours()
    {
        return $this->hasMany(MatchTeamPlayerRun::class,'match_team_player_bowler_id')->where("status","four");
    }
	
	/**
     * Scope a query to only include sixes runs .
     *
	 *@return \Illuminate\Database\Eloquent\Relations\HasMany
     *@return \Illuminate\Database\Eloquent\Builder    
     */
	 
	public function sixes()
    {
        return $this->hasMany(MatchTeamPlayerRun::class,'match_team_player_bowler_id')->where("status","six");
    }
	
	/**
     * Scope a query to only include two runs .
     *
	 *@return \Illuminate\Database\Eloquent\Relations\HasMany
     *@return \Illuminate\Database\Eloquent\Builder    
     */
	 
	public function twos()
    {
        return $this->hasMany(MatchTeamPlayerRun::class,'match_team_player_bowler_id')->where("status","two");
    }
		
	/**
     * Scope a query to only include one runs .
     *
	 *@return \Illuminate\Database\Eloquent\Relations\HasMany
     *@return \Illuminate\Database\Eloquent\Builder    
     */
	 
	public function ones()
    {
        return $this->hasMany(MatchTeamPlayerRun::class,'match_team_player_bowler_id')->where("status","one");
    }
		
	/**
     * Scope a query to only include no runs .
     *
	 *@return \Illuminate\Database\Eloquent\Relations\HasMany
     *@return \Illuminate\Database\Eloquent\Builder    
     */
	 
	public function dots()
    {
        return $this->hasMany(MatchTeamPlayerRun::class,'match_team_player_bowler_id')->where("status","dot");
    }	
}