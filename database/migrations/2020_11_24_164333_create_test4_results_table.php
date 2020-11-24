<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTest4ResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test4_results', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('datetime');
            $table->foreignId('idTest')->references('id')->on('test1_tests');
            $table->foreignId('idUser')->references('id')->on('users');
            $table->string('userName');
            $table->unsignedInteger('point');
            $table->longText('details');
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
        Schema::dropIfExists('test4_results');
    }
}
