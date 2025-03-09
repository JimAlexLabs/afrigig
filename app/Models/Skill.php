<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'is_verified',
        'demand_score'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'demand_score' => 'integer'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot('proficiency_level', 'years_experience', 'is_verified')
            ->withTimestamps();
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_skills');
    }

    public function getVerifiedUsersCount()
    {
        return $this->users()
            ->wherePivot('is_verified', true)
            ->count();
    }

    public function getAverageYearsExperience()
    {
        return $this->users()
            ->avg('years_experience') ?? 0;
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeInDemand($query, $minScore = 70)
    {
        return $query->where('demand_score', '>=', $minScore);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
} 