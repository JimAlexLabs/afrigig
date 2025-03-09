<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    public function update(User $user, Job $job): bool
    {
        return $user->id === $job->client_id;
    }

    public function delete(User $user, Job $job): bool
    {
        return $user->id === $job->client_id && $job->status === 'open';
    }

    public function bid(User $user, Job $job): bool
    {
        return $user->role === 'freelancer' && 
               $job->status === 'open' && 
               $user->id !== $job->client_id &&
               !$job->bids()->where('freelancer_id', $user->id)->exists();
    }
} 