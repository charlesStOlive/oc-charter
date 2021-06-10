<?php namespace Waka\Charter\Updates;

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
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
