<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string("image");
			$table->string("name");
			$table->date("birth_date");
			$table->enum("player_type",['batsman','bowler','wicketkeeper','allrounder']);
			$table->integer("total_runs_played");
			$table->integer("total_runs_given");
			$table->integer("total_fours");
			$table->integer("total_sixes");
			$table->integer("fifties");
			$table->integer("hundreds");
			$table->integer("best_runs");
			$table->integer("wickets");			
			$table->float("total_balls_hit");
			$table->float("total_balls_threw");
			$table->unsignedBigInteger("team_id");
			$table->foreign("team_id")->references("id")->on("teams");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
