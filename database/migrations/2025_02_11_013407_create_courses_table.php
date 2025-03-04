<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disabilita i vincoli
        Schema::dropIfExists('teachers_courses'); // Elimina prima la tabella pivot
        Schema::dropIfExists('courses'); // Ora si può eliminare senza errori
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Riabilita i vincoli
    }
};
