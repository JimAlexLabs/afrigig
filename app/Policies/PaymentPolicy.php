<?php

namespace App\Policies;

use App\Models\Milestone;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function pay(User $user, Milestone $milestone): bool
    {
        // Only the client can make payments
        return $user->role === 'client' && 
               $user->id === $milestone->job->client_id &&
               $milestone->status !== 'paid';
    }
} 