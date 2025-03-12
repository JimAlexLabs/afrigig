<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SkillAssessment extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'skill_id',
        'title',
        'description',
        'difficulty',
        'time_limit',
        'passing_score',
        'is_active',
        'questions',
        'status'
    ];

    protected $casts = [
        'questions' => 'array',
        'is_active' => 'boolean',
        'time_limit' => 'integer',
        'passing_score' => 'integer'
    ];

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'description', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the skill that owns the assessment.
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Get the questions for the assessment.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the attempts for the assessment.
     */
    public function attempts()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }

    public function getPassRateAttribute()
    {
        $totalAttempts = $this->attempts()->count();
        if ($totalAttempts === 0) return 0;
        
        $passedAttempts = $this->attempts()->where('passed', true)->count();
        return ($passedAttempts / $totalAttempts) * 100;
    }

    public function getAverageScoreAttribute()
    {
        return $this->attempts()->avg('score') ?? 0;
    }

    public function getAverageTimeAttribute()
    {
        return $this->attempts()->avg('time_taken') ?? 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function isAvailableForUser(User $user)
    {
        $lastAttempt = $this->attempts()->where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$lastAttempt) return true;

        // If user passed, no more attempts needed
        if ($lastAttempt->passed) return false;

        // Check if enough time has passed since last attempt (e.g., 7 days)
        return $lastAttempt->completed_at->addDays(7)->isPast();
    }

    public function getQuestionCount()
    {
        return count($this->questions);
    }

    public function calculateScore(array $userAnswers)
    {
        $totalQuestions = $this->getQuestionCount();
        $correctAnswers = 0;

        foreach ($this->questions as $index => $question) {
            if (isset($userAnswers[$index]) && $userAnswers[$index] === $question['correct_answer']) {
                $correctAnswers++;
            }
        }

        return ($correctAnswers / $totalQuestions) * 100;
    }

    public function generateFeedback(SkillAssessmentAttempt $attempt)
    {
        $feedback = [
            'score' => $attempt->score,
            'passed' => $attempt->passed,
            'time_taken' => $attempt->time_taken,
            'improvement_areas' => [],
            'correct_answers' => [],
            'wrong_answers' => []
        ];

        foreach ($this->questions as $index => $question) {
            $userAnswer = $attempt->answers[$index] ?? null;
            $isCorrect = $userAnswer === $question['correct_answer'];

            if ($isCorrect) {
                $feedback['correct_answers'][] = $index;
            } else {
                $feedback['wrong_answers'][] = [
                    'question_index' => $index,
                    'user_answer' => $userAnswer,
                    'correct_answer' => $question['correct_answer'],
                    'explanation' => $question['explanation'] ?? null
                ];
                
                if (isset($question['category'])) {
                    $feedback['improvement_areas'][] = $question['category'];
                }
            }
        }

        $feedback['improvement_areas'] = array_unique($feedback['improvement_areas']);
        
        return $feedback;
    }
}
