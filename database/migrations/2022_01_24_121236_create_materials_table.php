<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('course_id');
            $table->foreign('course_id')->references('course_id')->on('course_sections')->onDelete('cascade');
            $table->index('course_id');
            $table->longText('teams_url')->nullable();
            $table->string('file_name')->nullable();
            $table->longText('content');
            $table->string('title');
            $table->boolean('isAssignment');
            $table->boolean('isExam');
            $table->boolean('isContent');
            $table->integer('week');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
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
        Schema::dropIfExists('materials');
    }
}
