<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'email',
        'role_id',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    protected $with = ['role'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    // Constantes pour les rôles
    const ROLE_ADMIN = 'administrator';
    const ROLE_READER = 'reader';
    const ROLE_EXTENDED_READER = 'extended_reader';
}
