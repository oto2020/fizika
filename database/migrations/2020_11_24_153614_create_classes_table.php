<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // id, school_id, name, uri, teacher_name, classroom_teacher_name
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {

            $table->increments('id');
            $table->foreignId('school_id')->references('id')->on('schools');

            $table->string('name');
            $table->string('uri');
            $table->string('teacher_name');             // имя школьного учителя физики
            $table->string('classroom_teacher_name');   // имя классного руководителя

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
        Schema::dropIfExists('classes');
    }
}
