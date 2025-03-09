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
        'name',
        'email',
        'password',
        'role', // 'freelancer', 'client', 'admin'
        'phone',
        'country',
        'bio',
        'skills',
        'portfolio_url',
        'avatar',
        'is_verified',
        'rating',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'skills' => 'array',
        'is_verified' => 'boolean',
        'rating' => 'float',
    ];

    // Relationships
    public function postedJobs()
    {
        return $this->hasMany(Job::class, 'client_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'freelancer_id');
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function trainings()
    {
        return $this->belongsToMany(Training::class)
            ->withTimestamps()
            ->withPivot(['progress', 'completed']);
    }
} 