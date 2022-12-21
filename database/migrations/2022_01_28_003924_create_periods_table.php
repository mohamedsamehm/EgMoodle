<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('period');
            $table->time('time');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('periods');
    }
}
