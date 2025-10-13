<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', // <— penting untuk middleware/policy admin
    ];

    /**
     * Hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean', // <— supaya kebaca sebagai bool
        ];
    }

    /**
     * Relasi: 1 user bisa punya banyak suara (satu per posisi).
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Helper: apakah user admin?
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }
}
