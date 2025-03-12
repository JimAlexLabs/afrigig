<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'profile_photo',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
        'website',
        'skills',
        'experience_level',
        'hourly_rate',
        'availability',
        'google_id',
        'linkedin_id',
        'terms_accepted',
        'balance',
        'settings',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'terms_accepted' => 'boolean',
        'skills' => 'array',
        'settings' => 'array',
        'balance' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
    ];

    /**
     * Get the jobs posted by the user.
     */
    public function postedJobs()
    {
        return $this->hasMany(Job::class, 'client_id');
    }

    /**
     * Get the jobs assigned to the user.
     */
    public function assignedJobs()
    {
        return $this->hasMany(Job::class, 'freelancer_id');
    }

    /**
     * Get the bids submitted by the user.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class, 'freelancer_id');
    }

    /**
     * Get the user's cart items.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the user's payments.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the user's skill assessments.
     */
    public function skillAssessments()
    {
        return $this->hasMany(SkillAssessment::class);
    }

    /**
     * Get the user's messages.
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the user's received messages.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a freelancer.
     */
    public function isFreelancer()
    {
        return $this->role === 'freelancer';
    }

    /**
     * Check if the user is a client.
     */
    public function isClient()
    {
        return $this->role === 'client';
    }

    /**
     * Check if the user has completed skill assessment.
     */
    public function hasCompletedSkillAssessment()
    {
        return $this->skillAssessments()->where('status', 'passed')->exists();
    }
} 