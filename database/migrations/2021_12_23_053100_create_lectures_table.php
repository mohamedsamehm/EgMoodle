<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('course_id');
            $table->foreign('course_id')->references('course_id')->on('course_sections')->onDelete('cascade');
            $table->index('course_id');
            $table->bigInteger('section_id')->unsigned();
            $table->foreign('section_id')->references('section_id')->on('course_sections')->onDelete('cascade');
            $table->index('section_id');
            $table->bigInteger('professor_id')->unsigned();
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
            $table->index('professor_id'); 
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
        Schema::dropIfExists('lectures');
    }
}
