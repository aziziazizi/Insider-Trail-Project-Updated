<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTeamPlayerRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_team_player_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer("runs");
			$table->enum("status",['one','two','four','six','bold','lbw','catch','stump','dot']);
			$table->text('description');
			$table->unsignedBigInteger("match_team_player_bowler_id");
			$table->unsignedBigInteger("match_team_player_bats_man_id");
			$table->unsignedBigInteger("partner_id");
            $table->timestamps();
			$table->foreign("match_team_player_bowler_id")->references("id")->on("match_team_player_bowlers");
			$table->foreign("match_team_player_bats_man_id")->references("id")->on("match_team_player_bats_men");
			$table->foreign("partner_id")->references("id")->on("match_team_player_bats_men");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_team_player_goal');
    }
}
