<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {

            $table->increments('id');

            $table->foreignId('test_id')->references('id')->on('tests');
            $table->foreignId('user_id')->references('id')->on('users');

            $table->dateTime('datetime');
            $table->string('user_name');
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
        Schema::dropIfExists('test_results');
    }
}
