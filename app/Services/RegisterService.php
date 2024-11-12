<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RegisterService
{
    /**
     * Store a new user in the database.
     *
     * @param  array<string, string>  $userData  An associative array containing user data.
     * @return User The created User model.
     */
    public function storeUser(array $userData): User
    {
        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
        ]);
    }

    /**
     * Assign a role to a user.
     *
     * @param  User  $user  The user to assign the role to.
     * @param  string  $roleName  The name of the role to assign.
     */
    public function assignRoleToUser(User $user/*, string $roleName*/): void
    {
        $role = 'user'; // Fissato a User
        $user->assignRole($role);
    }
}
