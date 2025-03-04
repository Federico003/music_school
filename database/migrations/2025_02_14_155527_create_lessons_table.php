<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLessonsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('lessons')) {
            Schema::create('lessons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_enrollment_id')->constrained()->onDelete('cascade');
                $table->date('day');
                $table->time('time');
                $table->integer('duration')->unsigned();    //Durata in minuti
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disabilita i vincoli
        Schema::dropIfExists('lessons');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Riabilita i vincoli
    }
}
