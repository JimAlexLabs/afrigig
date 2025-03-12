<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Bid;
use App\Models\Cart;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Jobs Statistics
        $totalJobs = Job::count();
        $jobsThisMonth = Job::whereMonth('created_at', Carbon::now()->month)->count();
        $jobsLastMonth = Job::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        $jobGrowth = $jobsLastMonth > 0 ? (($jobsThisMonth - $jobsLastMonth) / $jobsLastMonth) * 100 : 0;

        // User's Bids
        $activeBids = Bid::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->count();
        $completedJobs = Bid::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Earnings
        $totalEarnings = Bid::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount');
        $earningsThisMonth = Bid::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');
        $earningsLastMonth = Bid::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('amount');
        $earningsGrowth = $earningsLastMonth > 0 ? (($earningsThisMonth - $earningsLastMonth) / $earningsLastMonth) * 100 : 0;

        // Recent Activity
        $recentJobs = Job::latest()->take(5)->get();
        $recentBids = Bid::where('user_id', $user->id)
            ->with('job')
            ->latest()
            ->take(5)
            ->get();

        // Cart Items
        $cartItemsCount = Cart::where('user_id', $user->id)->count();
        
        // Unread Notifications
        $unreadNotifications = $user->unreadNotifications->count();

        return view('dashboard', compact(
            'totalJobs',
            'jobsThisMonth',
            'jobGrowth',
            'activeBids',
            'completedJobs',
            'totalEarnings',
            'earningsGrowth',
            'recentJobs',
            'recentBids',
            'cartItemsCount',
            'unreadNotifications'
        ));
    }
} 