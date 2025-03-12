<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Job;
use App\Models\Bid;
use App\Models\Cart;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('components.sidebar.user-sidebar', function ($view) {
            $user = Auth::user();
            
            if ($user) {
                $view->with([
                    'recommendedJobsCount' => Job::where('active', true)
                        ->whereDoesntHave('bids', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })->count(),
                    
                    'activeBidsCount' => Bid::where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->count(),
                    
                    'cartItemsCount' => Cart::where('user_id', $user->id)
                        ->count(),
                    
                    'unreadMessagesCount' => Message::where('recipient_id', $user->id)
                        ->whereNull('read_at')
                        ->count(),
                ]);
            }
        });
    }
} 