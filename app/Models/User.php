<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public const ROLE_OWNER = 'owner';

    public const ROLE_KEUANGAN = 'keuangan';

    public const ROLE_KASIR = 'kasir';

    public const ROLES = [self::ROLE_KEUANGAN, self::ROLE_OWNER, self::ROLE_KASIR];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isKeuangan(): bool
    {
        return $this->role === self::ROLE_KEUANGAN;
    }

    public function isKasir(): bool
    {
        return $this->role === self::ROLE_KASIR;
    }

    /**
     * Nama route halaman awal setelah login, sesuai role.
     */
    public function homeRoute(): ?string
    {
        return match ($this->role) {
            self::ROLE_KEUANGAN => 'dashboard',
            self::ROLE_OWNER => 'owner-beranda',
            self::ROLE_KASIR => 'kasir.pos',
            default => null,
        };
    }
}
