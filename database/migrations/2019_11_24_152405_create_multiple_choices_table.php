<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultipleChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multiple_choices', function (Blueprint $table) {
            $table->bigIncrements('mc_id');
            $table->bigInteger('qb_id')->unsigned();
            $table->foreign('qb_id')->references('qb_id')->on('question_banks');
            $table->text('choices_description');
            $table->smallInteger('mc_is_answer');
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
        Schema::dropIfExists('multiple_choices');
    }
}
