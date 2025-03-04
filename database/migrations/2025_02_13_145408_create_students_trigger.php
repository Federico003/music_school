<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStudentsTrigger extends Migration
{
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER update_students_table
            AFTER INSERT ON model_has_roles
            FOR EACH ROW
            BEGIN
                IF (SELECT name FROM roles WHERE id = NEW.role_id) = 'student' THEN
                    INSERT IGNORE INTO students (id, name, email, created_at, updated_at)
                    SELECT users.id, users.name, users.email, users.created_at, users.updated_at
                    FROM users
                    WHERE users.id = NEW.model_id;
                END IF;
            END;
        ");
    }

    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS update_students_table");
    }
}
