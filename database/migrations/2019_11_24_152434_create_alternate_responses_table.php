<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlternateResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alternate_responses', function (Blueprint $table) {
            $table->bigIncrements('ar_id');
            $table->bigInteger('qb_id')->unsigned();
            $table->foreign('qb_id')->references('qb_id')->on('question_banks');
            $table->smallInteger('ar_is_answer');
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
        Schema::dropIfExists('alternate_responses');
    }
}
