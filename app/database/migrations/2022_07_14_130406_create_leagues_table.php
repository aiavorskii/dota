<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->integer('league_external_id')->index('league_external_id_index');;
            $table->string('name');
            $table->tinyInteger('tier');
            $table->tinyInteger('region');
            $table->tinyInteger('status');
            $table->integer('most_recent_activity');
            $table->integer('start_timestamp')->index('start_timestamp_index');
            $table->integer('end_timestamp');
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
        Schema::dropIfExists('leagues');
    }
}
