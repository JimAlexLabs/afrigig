<?php

namespace App\Policies;

use App\Models\SkillAssessmentAttempt;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SkillAssessmentAttemptPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SkillAssessmentAttempt $attempt): bool
    {
        return $user->id === $attempt->user_id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, SkillAssessmentAttempt $attempt): bool
    {
        return $user->id === $attempt->user_id && !$attempt->isCompleted();
    }

    public function delete(User $user, SkillAssessmentAttempt $attempt): bool
    {
        return $user->isAdmin();
    }

    public function provideFeedback(User $user, SkillAssessmentAttempt $attempt): bool
    {
        return $user->isAdmin() && $attempt->isCompleted();
    }

    public function viewResults(User $user, SkillAssessmentAttempt $attempt): bool
    {
        return $user->id === $attempt->user_id || $user->isAdmin();
    }
} 