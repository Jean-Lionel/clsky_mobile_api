<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role' => UserRole::class
    ];

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMINISTRATEUR;
    }

    public function isEnqueteur(): bool
    {
        return $this->role === UserRole::ENQUETEUR;
    }

    public function isUtilisateur(): bool
    {
        return $this->role === UserRole::UTILISATEUR;
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}