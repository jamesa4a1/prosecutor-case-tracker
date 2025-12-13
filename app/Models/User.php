<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Role constants
    public const ROLE_ADMIN = 'Admin';
    public const ROLE_PROSECUTOR = 'Prosecutor';
    public const ROLE_CLERK = 'Clerk';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role checks
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isProsecutor(): bool
    {
        return $this->role === self::ROLE_PROSECUTOR;
    }

    public function isClerk(): bool
    {
        return $this->role === self::ROLE_CLERK;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // Relationships
    public function prosecutor()
    {
        return $this->hasOne(Prosecutor::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function statusChanges()
    {
        return $this->hasMany(StatusHistory::class, 'changed_by');
    }
}
