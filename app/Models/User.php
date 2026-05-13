<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'rol',
        'estado',
        'avatar',
    ];

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function isEmpresa(): bool
    {
        return $this->rol === 'empresa';
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    public function reservas()
    {
        return $this->hasMany(\App\Models\Reserva::class, 'usuario_id');
    }

    public function empresa()
    {
        return $this->hasOne(\App\Models\Empresa::class, 'usuario_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}