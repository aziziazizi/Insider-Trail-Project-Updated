<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\MatchTeamPlayerBatsMan;
use App\MatchTeamPlayerBowler;
use App\MatchTeamPlayerRun;

class MatchTeamPlayerRun extends Model
{
     /**
     * required
     * @var array
     */
	 
    protected $fillable = [
        'player_id', 'match_team_id', 'runs', 'status', 'partner_id'
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
     * Get the batsman for.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
	
	public function batsman()
    {
        return $this->belongsTo(MatchTeamPlayerBatsMan::class,'match_team_player_bats_man_id');
    }
		
	/**
     * Get the bowler for.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
	
	public function bowler()
    {
        return $this->belongsTo(MatchTeamPlayerBowler::class,'match_team_player_bowler_id');
    }
	
	/**
     * Get the partner batsman for.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
	
	public function bowler()
    {
        return $this->belongsTo(MatchTeamPlayerBatsMan::class,'partner_id');
    }
}
