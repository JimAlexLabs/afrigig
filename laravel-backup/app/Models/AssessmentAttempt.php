<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'skill_assessment_id',
        'score',
        'status',
        'completed_at',
        'time_taken'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'time_taken' => 'integer',
        'score' => 'float'
    ];

    /**
     * Get the user that made the attempt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the skill assessment that was attempted.
     */
    public function skillAssessment()
    {
        return $this->belongsTo(SkillAssessment::class);
    }

    /**
     * Get the answers for this attempt.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Check if the attempt was successful.
     */
    public function isSuccessful(): bool
    {
        return $this->score >= $this->skillAssessment->passing_score;
    }
} 