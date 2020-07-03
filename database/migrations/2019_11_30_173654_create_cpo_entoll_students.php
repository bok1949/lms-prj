<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpoEntollStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpo_enroll_students', function (Blueprint $table) {
            $table->bigIncrements('cpoes_id');

            $table->bigInteger('cpoes_stud_id')->unsigned();
            $table->foreign('cpoes_stud_id')->references('stud_id')->on('students');
           
            $table->bigInteger('cpo_id')->unsigned();
            $table->foreign('cpo_id')->references('cpo_id')->on('course_program_offers');

            $table->string('status')->nullable();
            
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
        Schema::dropIfExists('cpo_enroll_students');
    }
}
