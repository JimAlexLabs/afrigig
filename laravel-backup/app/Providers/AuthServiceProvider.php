<?php

namespace App\Providers;

use App\Models\Bid;
use App\Policies\BidPolicy;
use App\Models\Cart;
use App\Policies\CartPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Bid::class => BidPolicy::class,
        Cart::class => CartPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
} 