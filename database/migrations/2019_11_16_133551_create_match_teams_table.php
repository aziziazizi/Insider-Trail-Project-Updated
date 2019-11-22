<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_teams', function (Blueprint $table) {
            $table->bigIncrements('id');			
			$table->string("team_type");
			$table->string("stadium");
			$table->enum("started",['yes','no'])->default("no");
			$table->string("toss");	
			$table->string("elected_to");
			$table->integer("runs");	
			$table->integer("played_balls");	
			$table->integer("run_rate");	
			$table->integer("wicket_lost");	
			$table->integer("total_balls");	
			$table->integer('wide_balls');
            $table->integer('no_balls');			
            $table->string('start_time');			
            $table->string('end_time');			
			$table->enum("result",['won','lost','drawn','null'])->default('null');				
			$table->unsignedBigInteger("team_id");
			$table->unsignedBigInteger("match_id");
            $table->timestamps();
			$table->foreign("team_id")->references("id")->on("teams");
			$table->foreign("match_id")->references("id")->on("matches");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_teams');
    }
}
