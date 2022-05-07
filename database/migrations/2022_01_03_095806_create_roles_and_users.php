<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use App\Models\User as User;
 
class CreateRolesAndUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rolesNames = [
            'admin',
            'user',
        ];

        foreach (User::$roles as $role => $name) {
            Role::create(['name' => $role]);
        }

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        
        //User::where('email', 'admin@example.com')->first()->assignRole('admin');
        User::whereEncrypted('email', 'admin@example.com')->first()->assignRole('admin');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        echo "I ruoli devono essere cancellati manualmente" . PHP_EOL;
    }
}
