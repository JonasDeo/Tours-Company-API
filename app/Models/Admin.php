<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    protected $fillable = ['name', 'email', 'password', 'avatar'];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    // ── JWT interface ─────────────────────────────────────────────────────────

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => 'admin',
        ];
    }
}