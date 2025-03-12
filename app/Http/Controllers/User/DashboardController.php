<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Bid;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get recent jobs that match user's skills
        $recommendedJobs = Job::whereActive(true)
            ->latest()
            ->take(5)
            ->get();
            
        // Get user's recent bids
        $recentBids = Bid::where('freelancer_id', $user->id)
            ->with('job')
            ->latest()
            ->take(5)
            ->get();
            
        // Get unread messages count
        $unreadMessages = Message::where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->count();
            
        // Get counts for sidebar
        $recommendedJobsCount = Job::whereActive(true)->count();
        $activeBidsCount = Bid::where('freelancer_id', $user->id)->where('status', 'pending')->count();
        $unreadMessagesCount = $unreadMessages;
        
        return view('dashboard', compact(
            'recommendedJobs',
            'recentBids',
            'unreadMessages',
            'recommendedJobsCount',
            'activeBidsCount',
            'unreadMessagesCount'
        ));
    }
}
