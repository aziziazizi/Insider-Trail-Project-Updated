<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use MatchTeamPlayerRun;
use MatchTeamPlayerBatsMan;
use MatchTeamPlayerBowler;

class Player extends Model
{
    
	/**
     * required
     * @var array
     */
	 
    protected $fillable = [
        'name', 'date_of_birth'
    ];

   	/**
     * MySql Type Casting
	 * @var array
     */
	 
    protected $casts = [      
        'date_of_birth' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    /**
     * Get the runs for the batsman.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
	 
    public function runs()
    {
        return $this->hasManyThrough(MatchTeamPlayerRun::class,MatchTeamPlayerBatsMan::class,'match_team_id','match_team_player_bats_man_id');
    }
		
    /**
     *  Get bowls for the batsman.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
	 
    public function bowls()
    {
        return $this->hasManyThrough(MatchTeamPlayerRun::class,MatchTeamPlayerBowler::class,'match_team_id','match_team_player_bowler_id');
    }
	
	 /**
     *  Get runs for the batsman as partner.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
	 
    public function partner_runs()
    {
        return $this->hasManyThrough(MatchTeamPlayerRun::class,MatchTeamPlayerBatsMan::class,'match_team_id','partner_id');
    }
}
