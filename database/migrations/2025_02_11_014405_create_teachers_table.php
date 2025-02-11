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
                //$table->string('name');
            });
        }

        // Popolare la tabella con i dati iniziali
        DB::statement("
            INSERT INTO teachers (id)
            SELECT model_id FROM model_has_roles
            JOIN roles ON model_has_roles.role_id = roles.id
            WHERE roles.name = 'teacher';
        ");
    }

    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}