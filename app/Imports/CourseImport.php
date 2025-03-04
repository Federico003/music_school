<?php

namespace App\Imports;

use App\Models\Course;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class CourseImport implements ToCollection
{
    /**
     * @param  Collection  $collection
     *
     * Importa un file excel contenente utenti da aggiungere al database.
     * La prima riga del file  contiene l'intestazione e viene ignorata.
     * Le colonne del file devono contenere i seguenti dati:
     * - Colonna 1: ID utente (non utilizzato)
     * - Colonna 2: Descrizione
     *
     * Se un corso con lo stesso nome esiste già nel database, lo salto.
     * Se un corso non esiste, lo creo.
     * @return void
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            // Salta la riga di intestazione
            if ($key == 0) {
                continue;
            }

            // Pulisco i dati in ingresso
            $name = $this->cleanUpOrNullValue($row[1]) ?? 'Course';
            $description = $this->cleanUpOrNullValue($row[2]) ?? 'Description';

            // Se esiste un utente con lo stesso email, lo salto
            $existingCourse = Course::where('name', Encrypter::encrypt($name))->first();
            if ($existingCourse) {
                continue;

            } else {
                $course = Course::create([
                    'name' => $name,
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Cleans up the input value by trimming whitespace.
     * Returns null if the resulting string is empty.
     *
     * @param  mixed  $value  The value to be cleaned up.
     * @return string|null The trimmed value or null if empty.
     */
    private function cleanUpOrNullValue($value)
    {
        if (strlen(trim($value)) == 0) {
            return null;
        }

        return trim($value);
    }

    public function model(array $row)
    {
        // Usa il modello Course per salvare i dati direttamente nella tabella 'courses'
        return new Course([
            'name' => $row[0],       // Prima colonna del file Excel
            'description' => $row[1], // Seconda colonna del file Excel
        ]);
    }
}
