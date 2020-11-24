<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTest1TestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test1_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('idLesson')->references('id')->on('lessons');

            $table->string('name');
            $table->string('previewText', 500);

            $table->string('user');

            $table->string('url');
            $table->string('fullUrl');

            $table->boolean('isDeleted')->default(false);
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
        Schema::dropIfExists('test1_tests');
    }
}
