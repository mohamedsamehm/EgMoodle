<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAnswersTable extends Migration
{
    public function up()
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('answer_id')->unsigned();
            $table->foreign('answer_id')->references('id')->on('options')->onDelete('cascade');
            $table->index('answer_id');
            $table->bigInteger('student_exam_id')->unsigned();
            $table->foreign('student_exam_id')->references('id')->on('student_exams')->onDelete('cascade');
            $table->index('student_exam_id');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('student_answers');
    }
}
