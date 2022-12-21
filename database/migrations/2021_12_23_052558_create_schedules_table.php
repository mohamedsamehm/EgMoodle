<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('day');
            $table->bigInteger('period_from')->unsigned();
            $table->foreign('period_from')->references('id')->on('sections')->onDelete('cascade');
            $table->index('period_from');
            $table->bigInteger('period_to')->unsigned();
            $table->foreign('period_to')->references('id')->on('sections')->onDelete('cascade');
            $table->index('period_to');
            $table->string('place');
            $table->boolean('isSection');
            $table->boolean('isLab');
            $table->boolean('isLecture');
            $table->string('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->index('course_id');
            $table->bigInteger('section_id')->unsigned();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->index('section_id');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
