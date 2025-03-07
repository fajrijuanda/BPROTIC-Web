<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'username', // Gantilah 'name' dengan 'username' sesuai perubahan database
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    
    
    public static function getUserWithRole($userId)
    {
        return self::with('role')->find($userId);
    }
    
    // Relasi ke Profile
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // Relasi ke StudentInterest (untuk Student)
    public function studentInterest()
    {
        return $this->hasOne(StudentInterest::class);
    }

    // Relasi ke UserDivision (untuk Admin, Instructor, Super Admin)
    public function userDivision()
    {
        return $this->hasOne(UserDivision::class);
    }
}
