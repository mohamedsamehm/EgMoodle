<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionAndLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_and_labs', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('course_id');
            $table->foreign('course_id')->references('course_id')->on('course_sections')->onDelete('cascade');
            $table->index('course_id');
            $table->bigInteger('section_id')->unsigned();
            $table->foreign('section_id')->references('section_id')->on('course_sections')->onDelete('cascade');
            $table->index('section_id');
            $table->bigInteger('engineer_id')->unsigned();
            $table->foreign('engineer_id')->references('id')->on('engineers')->onDelete('cascade');
            $table->index('engineer_id'); 
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
        Schema::dropIfExists('section_and_labs');
    }
}
