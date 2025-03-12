<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Bid;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with key metrics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get total users count
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', today())->count();
        
        // Get jobs metrics
        $totalJobs = Job::count();
        $activeJobs = Job::whereActive(true)->count();
        
        // Get bids metrics
        $totalBids = Bid::count();
        $bidsToday = Bid::whereDate('created_at', today())->count();
        
        // Get earnings metrics
        $totalEarnings = Payment::whereStatus('completed')->sum('amount');
        $earningsToday = Payment::whereStatus('completed')
            ->whereDate('created_at', today())
            ->sum('amount');
            
        // Get recent activity
        $recentActivity = DB::table('activity_log')
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersToday',
            'totalJobs',
            'activeJobs',
            'totalBids',
            'bidsToday',
            'totalEarnings',
            'earningsToday',
            'recentActivity'
        ));
    }
}
