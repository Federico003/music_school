<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use ESolution\DBEncryption\Encrypter;
use Log;
use DB;

class UserImport implements ToCollection
{

    //protected $rows = [];

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

        //Log::info('2');
        //Log::info('Data imported:', $collection->toArray());

        foreach ($collection as $key => $row) {

            //salta la riga di intestazione
            if ($key == 0) {
                continue;
            }

            // Cerco se esiste un utente con la stessa email
            $email = trim($row[2]);
            $existingUser = User::where('email', Encrypter::encrypt($email))->first();

            if ($existingUser) {
                //Log::info("L'utente con email {$email} esiste già");
            } else {
                //Log::info("L'utente con email {$email} non esiste e verrà creato.");

                $newUser = User::create([
                    'name' => trim($row[1]),
                    'email' => $email,
                    'password' => (trim (Hash::make($row[4]))),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                //Log::info($this->rows);
            }

            if (isset($newUser)){
                $this->assignRoleToUser($newUser);
            }
        }
    }

    public function assignRoleToUser(User $user)
    {
        $role = 'user';

        if ($role) {
            $user->assignRole($role);

            //Log::info("Ruolo '{$role}' assegnato a {$user->email}");
        }
    }

    /*public function getRows(): array
    {
        return $this->rows;
    }*/
}

