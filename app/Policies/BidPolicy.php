<?php

namespace App\Policies;

use App\Models\Bid;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BidPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Bid $bid)
    {
        return $user->id === $bid->user_id && $bid->status === 'pending';
    }

    public function delete(User $user, Bid $bid)
    {
        return $user->id === $bid->user_id && $bid->status === 'pending';
    }

    public function accept(User $user, Bid $bid)
    {
        return $user->id === $bid->job->user_id && $bid->status === 'pending';
    }

    public function reject(User $user, Bid $bid)
    {
        return $user->id === $bid->job->user_id && $bid->status === 'pending';
    }
} 