<?php namespace Waka\Charter\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateChartsTable extends Migration
{
    public function up()
    {
        Schema::create('waka_charter_charts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_charter_charts');
    }
}
