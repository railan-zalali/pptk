<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    /**
     * Check if user is Admin PPTK (full CRUD access to operational data).
     */
    public function isAdminPptk(): bool
    {
        return $this->role === 'admin_pptk';
    }

    /**
     * Check if user is Manajemen (view-only access to monitoring data).
     */
    public function isManajemen(): bool
    {
        return $this->role === 'manajemen';
    }

    /**
     * Cek apakah user memiliki akses ke panel (admin_pptk ATAU manajemen).
     * Gunakan ini untuk membedakan user terdaftar dari guest.
     */
    public function isPrivileged(): bool
    {
        return in_array($this->role, ['admin_pptk', 'manajemen']);
    }

    /**
     * @deprecated Gunakan isPrivileged() atau isAdminPptk() secara eksplisit.
     * Dipertahankan untuk backward compat. Return true untuk admin_pptk DAN manajemen.
     */
    public function isAdmin(): bool
    {
        return $this->isPrivileged();
    }

    /**
     * Alias for isManajemen() - backward compat.
     */
    public function isManager(): bool
    {
        return $this->isManajemen();
    }
}
