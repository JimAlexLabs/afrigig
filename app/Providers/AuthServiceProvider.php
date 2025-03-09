<?php

namespace App\Providers;

use App\Models\Bid;
use App\Policies\BidPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Bid::class => BidPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
} 