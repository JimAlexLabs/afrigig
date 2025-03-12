<?php

namespace App\Policies;

use App\Models\SkillAssessment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SkillAssessmentPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view assessments
    }

    public function view(User $user, SkillAssessment $assessment): bool
    {
        return $assessment->is_active || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, SkillAssessment $assessment): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, SkillAssessment $assessment): bool
    {
        return $user->isAdmin();
    }

    public function attempt(User $user, SkillAssessment $assessment): bool
    {
        if (!$assessment->is_active) {
            return false;
        }

        if (!$assessment->isAvailableForUser($user)) {
            return false;
        }

        // Check if user has paid registration fee (if required)
        if ($user->isFreelancer() && !$user->registration_fee_paid) {
            return false;
        }

        return true;
    }

    public function manageResults(User $user): bool
    {
        return $user->isAdmin();
    }
} 