<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTeachersTable extends Migration
{
    public function up()
    {
        // Verifica se la tabella teachers esiste già
        if (!Schema::hasTable('teachers')) {
            Schema::create('teachers', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary(); // Stessa struttura della view
                $table->string('name');   // Nome insegnante
                $table->string('email')->unique();
                $table->timestamps();
            });
        }

        // Popolare la tabella con i dati iniziali
        DB::statement("
            INSERT INTO teachers (id, name, email, created_at, updated_at) 
                SELECT users.id, users.name, users.email, users.created_at, users.updated_at
                FROM users
                JOIN model_has_roles ON users.id = model_has_roles.model_id
                JOIN roles ON model_has_roles.role_id = roles.id
                WHERE roles.name = 'teacher';
        ");


    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disabilita i vincoli
        Schema::dropIfExists('teachers');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Riabilita i vincoli
    }
}