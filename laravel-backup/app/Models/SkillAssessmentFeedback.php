<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillAssessmentFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_assessment_attempt_id',
        'feedback',
        'improvement_areas',
        'recommended_resources',
        'feedback_date',
        'reviewed_by'
    ];

    protected $casts = [
        'improvement_areas' => 'array',
        'recommended_resources' => 'array',
        'feedback_date' => 'datetime'
    ];

    public function attempt()
    {
        return $this->belongsTo(SkillAssessmentAttempt::class, 'skill_assessment_attempt_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending()
    {
        return is_null($this->feedback_date);
    }

    public function isReviewed()
    {
        return !is_null($this->feedback_date);
    }

    public function addRecommendedResource($resource)
    {
        $resources = $this->recommended_resources ?? [];
        $resources[] = $resource;
        $this->recommended_resources = array_unique($resources);
        $this->save();
    }

    public function addImprovementArea($area)
    {
        $areas = $this->improvement_areas ?? [];
        $areas[] = $area;
        $this->improvement_areas = array_unique($areas);
        $this->save();
    }

    public function scopePending($query)
    {
        return $query->whereNull('feedback_date');
    }

    public function scopeReviewed($query)
    {
        return $query->whereNotNull('feedback_date');
    }

    public function scopeByReviewer($query, $reviewerId)
    {
        return $query->where('reviewed_by', $reviewerId);
    }
} 