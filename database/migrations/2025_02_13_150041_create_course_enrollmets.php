<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('course_enrollments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('course_id');
        $table->unsignedBigInteger('teacher_id');
        $table->timestamps();

        // Aggiungi i vincoli di integrità referenziale
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('course_enrollments');
}

};
