<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function users(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->get('role')) {
            $query->where('role', $request->get('role'));
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function jobs(Request $request)
    {
        $query = Job::with(['user', 'bids']);

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        $jobs = $query->latest()->paginate(10);

        return view('admin.jobs', compact('jobs'));
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['user', 'job']);

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('job', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        $payments = $query->latest()->paginate(10);

        return view('admin.payments', compact('payments'));
    }

    public function verifyUser(User $user)
    {
        $user->update(['is_verified' => true]);
        return back()->with('success', 'User verified successfully.');
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated successfully.');
    }

    public function showUser(User $user)
    {
        $user->load(['jobs', 'bids', 'payments']);
        
        $stats = [
            'total_jobs' => $user->jobs()->count(),
            'active_jobs' => $user->jobs()->where('status', 'active')->count(),
            'completed_jobs' => $user->jobs()->where('status', 'completed')->count(),
            'total_earnings' => $user->payments()->where('status', 'completed')->sum('amount'),
            'total_bids' => $user->bids()->count(),
            'accepted_bids' => $user->bids()->where('status', 'accepted')->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function deleteJob(Job $job)
    {
        $job->delete();
        return back()->with('success', 'Job deleted successfully.');
    }

    public function showJob(Job $job)
    {
        $job->load(['user', 'bids.user', 'milestones']);
        return view('admin.jobs.show', compact('job'));
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_jobs' => Job::count(),
            'total_earnings' => Payment::where('status', 'completed')->sum('amount'),
            'pending_verifications' => User::where('is_verified', false)->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_jobs = Job::with('user')->latest()->take(5)->get();
        $recent_payments = Payment::with(['user', 'job'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_jobs', 'recent_payments'));
    }

    public function showPayment(Payment $payment)
    {
        $payment->load(['user', 'job']);
        return view('admin.payments.show', compact('payment'));
    }

    public function processPayment(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment cannot be processed.');
        }

        try {
            // Process payment logic here
            $payment->update(['status' => 'completed']);
            return back()->with('success', 'Payment processed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }

    public function refundPayment(Payment $payment)
    {
        if ($payment->status !== 'completed') {
            return back()->with('error', 'This payment cannot be refunded.');
        }

        try {
            // Refund payment logic here
            $payment->update(['status' => 'refunded']);
            return back()->with('success', 'Payment refunded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to refund payment: ' . $e->getMessage());
        }
    }

    public function earningsReport(Request $request)
    {
        $period = $request->get('period', 'month');
        $start = Carbon::now();
        
        switch ($period) {
            case 'week':
                $start->subWeek();
                $groupBy = 'date';
                break;
            case 'month':
                $start->subMonth();
                $groupBy = 'date';
                break;
            case 'year':
                $start->subYear();
                $groupBy = 'month';
                break;
            default:
                $start->subMonth();
                $groupBy = 'date';
        }

        $earnings = Payment::where('status', 'completed')
            ->where('created_at', '>=', $start)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy($groupBy)
            ->get();

        return view('admin.reports.earnings', compact('earnings', 'period'));
    }

    public function usersReport(Request $request)
    {
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::where('is_verified', true)->count(),
            'clients' => User::where('role', 'client')->count(),
            'freelancers' => User::where('role', 'freelancer')->count(),
            'recent_signups' => User::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
        ];

        $registrations = User::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('month')
            ->get();

        return view('admin.reports.users', compact('stats', 'registrations'));
    }

    public function jobsReport(Request $request)
    {
        $stats = [
            'total_jobs' => Job::count(),
            'active_jobs' => Job::where('status', 'active')->count(),
            'completed_jobs' => Job::where('status', 'completed')->count(),
            'total_bids' => Bid::count(),
            'avg_bids_per_job' => round(Bid::count() / max(Job::count(), 1), 2),
            'avg_job_value' => round(Job::avg('budget'), 2),
        ];

        $jobs_by_status = Job::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $recent_activity = Job::with(['user', 'bids'])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.jobs', compact('stats', 'jobs_by_status', 'recent_activity'));
    }
} 