<?php

namespace App\Models;

use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use EncryptedAttribute, HasFactory, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be encrypted/decrypted.
     *
     * @var array<int, string>
     */
    protected $encryptable = [
        'name',
        'email',
    ];

    /**
     * User roles
     *
     * @var array<string, string>
     */
    protected static $roles = [
        'admin' => 'Amministratore',
        'user' => 'Utente',
        'student' => 'Studente',
        'teacher' => 'Insegnante'
    ];

    public static function getTranslatedRole($role)
    {
        return self::$roles[$role] ?? $role;
    }

    /**
     * User statuses
     *
     * @var array<int, string>
     */
    protected static $statuses = [
        1 => 'Attivo',
        0 => 'Disattivo',
    ];

    /**
     * Get the available user roles.
     *
     * @return array<string, string> An array of user roles where keys are role codes and values are role names.
     */
    public static function getRoles(): array
    {
        return self::$roles;
    }

    /**
     * Get the available user statuses.
     *
     * @return array<int, string> An array of user statuses where keys are status codes and values are status names.
     */
    public static function getStatuses(): array
    {
        return self::$statuses;
    }

    /**
     * Get the user's role name.
     */
    public function getRoleName(): string
    {
        return self::$roles[$this->getRoleNames()->first()];
    }

    /**
     * Boot the User model and set up event listeners for creating, updating, and deleting actions.
     *
     * This method registers event listeners to log activities related to user records, such as creation, updating, and deletion.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function () {
            //Log::info('User creating');
        });

        self::created(function () {
            //Log::info('User created');
        });

        self::updating(function () {
            //Log::info('User updating');
        });

        self::updated(function () {
            //Log::info('User updated');
        });

        self::deleting(function () {
            //Log::info('User deleting');
        });

        self::deleted(function () {
            //Log::info('User deleted');
        });

        static::creating(function ($user) {
            $user->initial = strtoupper(substr($user->name, 0, 1));
        });

        static::updating(function ($user) {
            $user->initial = strtoupper(substr($user->name, 0, 1));
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name' => 'string',
            'email' => 'string',
            'password' => 'hashed',
            'last_login' => 'datetime',
            'status' => 'boolean',
        ];
    }

    /*public function courses()
{
    return $this->belongsToMany(Teacher::class, 'course_enrollments');
}*/

    public function teacherCourses()
    {
        return $this->belongsToMany(Course::class, 'teachers_courses', 'teacher_id', 'course_id');
    }

    public function studentCourses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments', 'user_id', 'course_id');
    }
}
