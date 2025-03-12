<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\Bid;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'totalJobs' => Job::where('user_id', $user->id)->count(),
            'totalBids' => Bid::where('user_id', $user->id)->count(),
            'successfulBids' => Bid::where('user_id', $user->id)
                ->where('status', 'accepted')
                ->count(),
            'earnings' => Bid::where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        return view('analytics.index', $data);
    }

    public function earnings()
    {
        $user = Auth::user();
        
        $earnings = Bid::where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('analytics.earnings', compact('earnings'));
    }

    public function performance()
    {
        $user = Auth::user();
        
        $performance = [
            'bidSuccessRate' => $this->calculateBidSuccessRate($user->id),
            'averageRating' => $this->calculateAverageRating($user->id),
            'completionRate' => $this->calculateCompletionRate($user->id),
            'responseTime' => $this->calculateAverageResponseTime($user->id),
        ];

        return view('analytics.performance', compact('performance'));
    }

    private function calculateBidSuccessRate($userId)
    {
        $totalBids = Bid::where('user_id', $userId)->count();
        $successfulBids = Bid::where('user_id', $userId)
            ->where('status', 'accepted')
            ->count();

        return $totalBids > 0 ? ($successfulBids / $totalBids) * 100 : 0;
    }

    private function calculateAverageRating($userId)
    {
        return 4.5; // Placeholder - implement actual rating calculation
    }

    private function calculateCompletionRate($userId)
    {
        $totalAccepted = Bid::where('user_id', $userId)
            ->where('status', 'accepted')
            ->count();
        $totalCompleted = Bid::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        return $totalAccepted > 0 ? ($totalCompleted / $totalAccepted) * 100 : 0;
    }

    private function calculateAverageResponseTime($userId)
    {
        return '2 hours'; // Placeholder - implement actual response time calculation
    }
} 