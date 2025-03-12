<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillAssessmentAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_assessment_id',
        'answers',
        'score',
        'passed',
        'time_taken',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'answers' => 'array',
        'score' => 'integer',
        'passed' => 'boolean',
        'time_taken' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assessment()
    {
        return $this->belongsTo(SkillAssessment::class, 'skill_assessment_id');
    }

    public function feedback()
    {
        return $this->hasOne(SkillAssessmentFeedback::class);
    }

    public function isCompleted()
    {
        return !is_null($this->completed_at);
    }

    public function isPending()
    {
        return !$this->isCompleted();
    }

    public function getTimeRemainingAttribute()
    {
        if ($this->isCompleted()) return 0;
        
        $timeLimit = $this->assessment->time_limit * 60; // Convert to seconds
        $timeElapsed = now()->diffInSeconds($this->started_at);
        
        return max(0, $timeLimit - $timeElapsed);
    }

    public function complete(array $answers)
    {
        $score = $this->assessment->calculateScore($answers);
        $timeTaken = ceil(now()->diffInMinutes($this->started_at));

        $this->update([
            'answers' => $answers,
            'score' => $score,
            'passed' => $score >= $this->assessment->passing_score,
            'time_taken' => $timeTaken,
            'completed_at' => now()
        ]);

        if ($this->passed) {
            // Update user skill verification
            $this->user->skills()->updateExistingPivot($this->assessment->skill_id, [
                'is_verified' => true,
                'verification_method' => 'test',
                'verified_at' => now()
            ]);
        }

        return $this->assessment->generateFeedback($this);
    }

    public function scopePassed($query)
    {
        return $query->where('passed', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('passed', false)->whereNotNull('completed_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('completed_at');
    }
} 