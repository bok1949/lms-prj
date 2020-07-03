<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_programs', function (Blueprint $table) {
            $table->bigInteger('stud_id')->unsigned();
            $table->foreign('stud_id')
                ->references('stud_id')->on('students')
                ->onDelete('cascade');
            $table->bigInteger('program_id')->unsigned();
            $table->foreign('program_id')
                ->references('program_id')->on('programs')
                ->onDelete('cascade');
            $table->primary(['stud_id', 'program_id']);
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
        Schema::dropIfExists('students_programs');
    }
}
