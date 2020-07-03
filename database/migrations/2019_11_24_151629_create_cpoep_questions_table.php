<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpoepQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpoep_questions', function (Blueprint $table) {
            //$table->bigIncrements('id');
            $table->bigInteger('qb_id')->unsigned();
            $table->foreign('qb_id')->references('qb_id')->on('question_banks');
            $table->bigInteger('cpoep_id')->unsigned();
            $table->foreign('cpoep_id')->references('cpoep_id')->on('cpo_evaluation_parts');
            $table->primary(['qb_id','cpoep_id']);
            $table->string('cpoepq_part')->nullable();
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
        Schema::dropIfExists('cpoep_questions');
    }
}
