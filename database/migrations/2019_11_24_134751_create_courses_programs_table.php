<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses_programs', function (Blueprint $table) {
            $table->bigIncrements('cp_id');

            $table->bigInteger('program_id')->unsigned();
            $table->foreign('program_id')
                ->references('program_id')->on('programs');
            $table->bigInteger('course_id')->unsigned();
            $table->foreign('course_id')
                ->references('course_id')->on('courses');
            $table->smallInteger('lab_units')->unsigned()->nullable();
            $table->smallInteger('lec_units')->unsigned()->nullable();
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
        Schema::dropIfExists('courses_programs');
    }
}
