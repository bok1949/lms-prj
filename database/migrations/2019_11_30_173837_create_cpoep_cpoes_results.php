<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpoepCpoesResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpoep_cpoes_results', function (Blueprint $table) {
            $table->bigInteger('cpoes_id')->unsigned();
            $table->foreign('cpoes_id')->references('cpoes_id')->on('cpo_enroll_students');
            
            $table->bigInteger('cpoep_id')->unsigned();
            $table->foreign('cpoep_id')->references('cpoep_id')->on('cpo_evaluation_parts');
            $table->primary(['cpoes_id', 'cpoep_id']);
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
        Schema::dropIfExists('cpoep_cpoes_results');
    }
}
