<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'icon',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'demand_score' => 'integer'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot('level', 'years_experience')
            ->withTimestamps();
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_skills');
    }

    // Helper Methods
    public function getPopularityAttribute()
    {
        return $this->users()->count();
    }

    public function getAverageExperienceAttribute()
    {
        return $this->users()->avg('user_skills.years_experience');
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