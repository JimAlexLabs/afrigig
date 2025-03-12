<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\SkillAssessment;
use Illuminate\Support\Facades\Auth;

class RequireSkillAssessment
{
    public function handle(Request $request, Closure $next)
    {
        $job = $request->route('job');
        
        if (!$job) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Skip for admin users
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Get required skills for the job
        $requiredSkills = $job->skills_required;

        // Get user's completed assessments
        $completedAssessments = SkillAssessment::whereHas('attempts', function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'completed')
                  ->where('score', '>=', 'skill_assessments.passing_score');
        })->pluck('category')->toArray();

        // Check if user has completed assessments for all required skills
        $missingSkills = array_diff($requiredSkills, $completedAssessments);

        if (!empty($missingSkills)) {
            return redirect()->route('skill-assessments.index')
                ->with('warning', 'You need to complete skill assessments for: ' . implode(', ', $missingSkills) . ' before applying to this job.');
        }

        return $next($request);
    }
} 