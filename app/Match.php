<?php

namespace App;
use App\League;
use  App\Team;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{   
    
    /**
     * required
     * @var array
     */
	 
    protected $fillable = [
        'played_at', 'round'
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
     * Get the teams for the specific Match.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	 
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'match_teams')->withTimestamps();
    }

    /**
     * Get the League for the match.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	 
    public function league()
    {
        return $this->belongsTo(League::class);
    }

    /**
     * Scope a query to only include for specific round .
     *@param integer $round
     *@return \Illuminate\Database\Eloquent\Builder
     */
	 
    public function for_round($round)
    {
        return $this->where('round', $round);
    }
	
    /**
     * Get the playing teams for the match.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	 
	public function match_teams()
    {
      return $this->hasMany(MatchTeam::class)->with("bowling_players","batting_players");
    } 

	/**
     * Get the playing teams for the match which is not started.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	 
	public function match_teams_not_started()
    {
      return $this->hasMany(MatchTeam::class)->where("started","no");
    }
	
	/**
     * Get the playing teams for the match which is started.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	 
	public function match_teams_started()
    {		
      return $this->hasMany(MatchTeam::class)->where("started","yes")->with("bowling_players","batting_players")->orderBy("elected_to","asc");
    }
		
    /**
     * Scope a query to only include not played matches.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
	 
    public function not_played($query)
    {
        return $query->where('played_at', 'pending');
    }
}
