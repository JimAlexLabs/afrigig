<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'assessment_attempt_id',
        'question_id',
        'answer',
        'is_correct',
        'points_earned'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'float'
    ];

    /**
     * Get the assessment attempt that owns the answer.
     */
    public function assessmentAttempt()
    {
        return $this->belongsTo(AssessmentAttempt::class);
    }

    /**
     * Get the question that was answered.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
} 