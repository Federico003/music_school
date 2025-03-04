<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        // Verifica se la tabella students esiste già
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary(); // ID dello studente
                $table->string('name'); // Nome dello studente
                $table->string('email')->unique(); // Email dello studente
                $table->timestamps(); // Timestamps di creazione e aggiornamento
            });
        }

        // Popolare la tabella students con i dati iniziali
        DB::statement("
            INSERT INTO students (id, name, email, created_at, updated_at) 
                SELECT users.id, users.name, users.email, users.created_at, users.updated_at
                FROM users
                JOIN model_has_roles ON users.id = model_has_roles.model_id
                JOIN roles ON model_has_roles.role_id = roles.id
                WHERE roles.name = 'student';
        ");
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disabilita i vincoli
        Schema::dropIfExists('students');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Riabilita i vincoli
    }
}
