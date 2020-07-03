<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpoCourseMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpo_course_materials', function (Blueprint $table) {
            $table->bigInteger('cpo_id')->unsigned();
            $table->foreign('cpo_id')->references('cpo_id')->on('course_program_offers');
            $table->bigInteger('cm_id')->unsigned();
            $table->foreign('cm_id')->references('cm_id')->on('course_materials');
            /* $table->primary('cpo_id', 'cm_id'); */
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
        Schema::dropIfExists('cpo_course_materials');
    }
}
