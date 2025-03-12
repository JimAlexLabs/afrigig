<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'skill_assessment_id',
        'content',
        'type',
        'options',
        'correct_answer',
        'points'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array'
    ];

    /**
     * Get the skill assessment that owns the question.
     */
    public function skillAssessment()
    {
        return $this->belongsTo(SkillAssessment::class);
    }

    /**
     * Get the test cases for the question.
     */
    public function testCases()
    {
        return $this->hasMany(TestCase::class);
    }

    /**
     * Get the answers for the question.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
} 