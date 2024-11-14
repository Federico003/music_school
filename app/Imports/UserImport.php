<?php

namespace App\Imports;

use App\Models\User;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{

    /**
     * @param Collection $collection
     * 
     * Importa un file excel contenente utenti da aggiungere al database.
     * La prima riga del file  contiene l'intestazione e viene ignorata.
     * Le colonne del file devono contenere i seguenti dati:
     * - Colonna 1: ID utente (non utilizzato)
     * - Colonna 2: Nome utente
     * - Colonna 3: Email utente
     * - Colonna 4: Password utente
     * 
     * Se un utente con la stessa email esiste gi  nel database, lo salto.
     * Se un utente non esiste, lo creo e gli assegno il ruolo "user".
     * 
     * @return void
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            // Salta la riga di intestazione
            if ($key == 0) { continue; }

            // Pulisco i dati in ingresso
            $name = $this->cleanUpOrNullValue($row[1]) ?? 'User';
            $email = $this->cleanUpOrNullValue($row[2]) ?? strtolower($name) .'@example.com';
            $password = $this->cleanUpOrNullValue($row[3]) ?? 'password';

            // Se esiste un utente con lo stesso email, lo salto 
            $existingUser = User::where('email', Encrypter::encrypt($email))->first();
            if ($existingUser) {
                continue;
            
            } else {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($user)) {
                $user->assignRole('user'); // Tutti i nuovi utenti sono di ruolo "user"
            }
        }
    }

    /**
     * Cleans up the input value by trimming whitespace. 
     * Returns null if the resulting string is empty.
     *
     * @param mixed $value The value to be cleaned up.
     * @return string|null The trimmed value or null if empty.
     */
    private function cleanUpOrNullValue($value) {
        if (strlen(trim($value)) == 0) {
            return null;
        }

        return trim($value);
    }
}
