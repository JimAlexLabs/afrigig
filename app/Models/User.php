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
        'role',
        'is_active',
        'is_verified',
        'avatar',
        'phone',
        'address',
        'city',
        'country',
        'bio',
        'skills',
        'experience_years',
        'hourly_rate',
        'education',
        'certifications',
        'registration_fee_paid',
        'payment_verified',
        'portfolio_url',
        'social_links',
        'languages',
        'availability_status',
        'rating',
        'completed_jobs_count',
        'success_rate'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'skills' => 'array',
        'social_links' => 'array',
        'languages' => 'array',
        'certifications' => 'array',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'registration_fee_paid' => 'boolean',
        'payment_verified' => 'boolean',
        'rating' => 'float',
        'completed_jobs_count' => 'integer',
        'success_rate' => 'float',
    ];

    // Relationships
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'freelancer_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot('proficiency_level', 'years_experience', 'is_verified')
            ->withTimestamps();
    }

    public function verifications()
    {
        return $this->hasMany(UserVerification::class);
    }

    public function skillAssessmentAttempts()
    {
        return $this->hasMany(SkillAssessmentAttempt::class);
    }

    public function isFreelancer()
    {
        return $this->role === 'freelancer';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getSuccessRateAttribute()
    {
        $total_jobs = $this->jobs()->count();
        $completed_jobs = $this->jobs()->where('status', 'completed')->count();
        
        return $total_jobs > 0 ? ($completed_jobs / $total_jobs) * 100 : 0;
    }

    public function getIsAvailableAttribute()
    {
        return $this->availability_status === 'available';
    }
} 