<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('material_sections', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('material_id')->unsigned();;
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->index('material_id');
            $table->bigInteger('section_id')->unsigned();
            $table->foreign('section_id')->references('section_id')->on('course_sections')->onDelete('cascade');
            $table->index('section_id');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('material_sections');
    }
}
