<?php

namespace App\Imports;

use App\Models\User;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{
    //protected $rows = [];

    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {

            // Salta la riga di intestazione
            if ($key == 0) {
                continue;
            }

            // Cerco se esiste un utente con la stessa email
            $email = trim($row[2]);
            $existingUser = User::where('email', Encrypter::encrypt($email))->first();

            if ($existingUser) {
                continue;
            
            } else {
                $newUser = User::create([
                    'name' => trim($row[1]),
                    'email' => $email,
                    'password' => (trim(Hash::make($row[4]))),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($newUser)) {
                $this->assignRoleToUser($newUser);
            }
        }
    }

    public function assignRoleToUser(User $user)
    {
        $role = 'user'; // Fissato ad User
        $user->assignRole($role);
    }
}
