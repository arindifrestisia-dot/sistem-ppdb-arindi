<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_PARENT = 'orang_tua';
    public const ROLE_COMMITTEE = 'panitia_ppdb';
    public const ROLE_PRINCIPAL = 'kepsek';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'password',
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

    public function studentRegistration(): HasOne
    {
        return $this->hasOne(StudentRegistration::class);
    }

    public function ppdbFormPayment(): HasOne
    {
        return $this->hasOne(PpdbFormPayment::class)->latestOfMany();
    }

    public function hasPaidPpdbForm(): bool
    {
        return (bool) $this->ppdbFormPayment?->isPaid();
    }

    public function isPanitia(): bool
    {
        return $this->hasRole(self::ROLE_COMMITTEE, 'panitia');
    }

    public function isKepsek(): bool
    {
        return $this->hasRole(self::ROLE_PRINCIPAL);
    }

    public function isParent(): bool
    {
        return $this->hasRole(self::ROLE_PARENT, 'parent');
    }

    public function isStaff(): bool
    {
        return $this->isPanitia() || $this->isKepsek();
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }
}
