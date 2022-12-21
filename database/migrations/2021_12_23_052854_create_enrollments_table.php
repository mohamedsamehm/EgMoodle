<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->index('student_id');
            $table->string('course_id');
            $table->foreign('course_id')->references('course_id')->on('course_sections')->onDelete('cascade');
            $table->index('course_id');
            $table->bigInteger('section_id')->unsigned();
            $table->foreign('section_id')->references('section_id')->on('course_sections')->onDelete('cascade');
            $table->index('section_id');
            $table->float('midterm')->nullable();
            $table->float('final')->nullable();
            $table->float('year_work')->nullable();
            $table->float('practical')->nullable();
            $table->float('total')->nullable();
            $table->string('grade')->nullable();
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
        Schema::dropIfExists('enrollments');
    }
}
