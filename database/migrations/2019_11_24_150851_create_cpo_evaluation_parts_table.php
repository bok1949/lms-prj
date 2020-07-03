<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpoEvaluationPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpo_evaluation_parts', function (Blueprint $table) {
            $table->bigIncrements('cpoep_id');
            $table->bigInteger('cpo_id')->unsigned();
            $table->foreign('cpo_id')->references('cpo_id')->on('course_program_offers');
            /* $table->text('cpoep_description')->nullable(); */
            /* $table->string('cpoep_parts')->nullable(); */
            $table->string('cpoep_type')->nullable();
            $table->boolean('cpoep_isactive');
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
        Schema::dropIfExists('cpo_evaluation_parts');
    }
}
