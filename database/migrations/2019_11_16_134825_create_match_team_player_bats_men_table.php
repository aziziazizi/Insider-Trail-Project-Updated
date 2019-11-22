<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTeamPlayerBatsMenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_team_player_bats_men', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ones');
            $table->integer('twos');
            $table->integer('fours');
            $table->integer('sixes');
            $table->float('runs');                     
            $table->enum('status',['out','not_out'])->default('not_out');
            $table->enum('status_type',['catch','bold','stump','lbw','run_out']);
            $table->string('description');			
            $table->integer('balls_played');
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
