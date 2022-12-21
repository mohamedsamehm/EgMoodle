<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->timestamp('deadline');
            $table->string('file_name');
            $table->bigInteger('material_id')->unsigned();
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->index('material_id');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
}
