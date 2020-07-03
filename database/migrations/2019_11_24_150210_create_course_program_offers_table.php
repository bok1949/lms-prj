<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseProgramOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_program_offers', function (Blueprint $table) {
            $table->bigIncrements('cpo_id');
            $table->bigInteger('cp_id')->unsigned();
            $table->foreign('cp_id')->references('cp_id')->on('courses_programs');
            $table->bigInteger('ins_id')->unsigned();
            $table->foreign('ins_id')->references('emp_id')->on('employees');
            $table->bigInteger('sc_id')->unique();
            $table->string('schedule');
            $table->string('ay');
            $table->string('term');
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
        Schema::dropIfExists('course_program_offers');
    }
}
