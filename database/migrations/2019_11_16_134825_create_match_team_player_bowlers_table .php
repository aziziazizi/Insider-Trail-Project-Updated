<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTeamPlayerBowlersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_team_player_bowlers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('balls_played');
            $table->integer('runs');
            $table->integer('maiden');
            $table->integer('wickets');
            $table->integer('wide_balls');
            $table->integer('no_balls');
			$table->unsignedBigInteger("match_team_id");
			$table->unsignedBigInteger("player_id");
            $table->timestamps();
			$table->foreign("match_team_id")->references("id")->on("match_teams");
			$table->foreign("player_id")->references("id")->on("players");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_team_players');
    }
}
