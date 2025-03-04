<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Log;


class Teacher extends Model
{
    use HasFactory;
    use EncryptedAttribute;

    // Seleziona i campi che possono essere assegnati in massa
    protected $fillable = ['name', 'email'];

    /**
     * The attributes that should be encrypted/decrypted.
     *
     * @var array<int, string>
     */
    protected $encryptable = [
        'name',
        'email',
    ];

    // Relazione many-to-many con il modello Course
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'teachers_courses'); // Specifica la tabella pivot 'teachers_courses'
    }

    // Accessore per decriptare il nome
    /*public function getNameAttribute($value)
    {
        try {
            return Crypt::decryptString($value); // Decripta il nome
        } catch (DecryptException $e) {
            // Log dell'errore per ulteriori diagnosi
        Log::error('Errore nella decriptazione del nome: ' . $e->getMessage());
        return 'Nome non disponibile';
        }
    }*/
    

    protected function casts(): array
    {
        return [
            'name' => 'string',
            'email' => 'string',
        ];
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_enrollment_id');
    }
}
