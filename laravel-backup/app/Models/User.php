<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'freelancer', 'client', 'admin'
        'avatar',
        'bio',
        'hourly_rate',
        'skills',
        'balance',
        'rating',
        'is_verified',
        'google_id',
        'linkedin_id',
        'terms_accepted',
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
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'skills' => 'array',
        'hourly_rate' => 'decimal:2',
        'balance' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_verified' => 'boolean',
        'terms_accepted' => 'boolean',
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
     * Get the user's cart items.
     */
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the user's bids.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class, 'freelancer_id');
    }

    /**
     * Get the user's jobs.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get the user's balance.
     */
    public function getBalanceAttribute()
    {
        return $this->attributes['balance'] ?? 0;
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get the unread messages for the user.
     */
    public function unreadMessages()
    {
        return $this->receivedMessages()->whereNull('read_at');
    }

    // Relationships
    public function postedJobs()
    {
        return $this->hasMany(Job::class, 'client_id');
    }

    public function receivedBids()
    {
        return $this->hasManyThrough(Bid::class, Job::class, 'client_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills');
    }

    // Helper Methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isFreelancer(): bool
    {
        return $this->role === 'freelancer';
    }

    public function canBid(): bool
    {
        return $this->is_verified && $this->isFreelancer();
    }
}
