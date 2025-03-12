<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Bid;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display overview dashboard with key metrics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function overview(Request $request)
    {
        // Get date range
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        
        // User metrics
        $userMetrics = [
            'total' => User::count(),
            'new' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'active' => User::where('status', 'active')->count(),
            'verified' => User::whereNotNull('email_verified_at')->count()
        ];
        
        // Job metrics
        $jobMetrics = [
            'total' => Job::count(),
            'active' => Job::whereStatus('active')->count(),
            'completed' => Job::whereStatus('completed')->count(),
            'new' => Job::whereBetween('created_at', [$startDate, $endDate])->count()
        ];
        
        // Bid metrics
        $bidMetrics = [
            'total' => Bid::count(),
            'pending' => Bid::whereStatus('pending')->count(),
            'accepted' => Bid::whereStatus('accepted')->count(),
            'new' => Bid::whereBetween('created_at', [$startDate, $endDate])->count()
        ];
        
        // Payment metrics
        $paymentMetrics = [
            'total' => Payment::whereStatus('completed')->sum('amount'),
            'pending' => Payment::whereStatus('pending')->sum('amount'),
            'refunded' => Payment::whereStatus('refunded')->sum('amount'),
            'period' => Payment::whereStatus('completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount')
        ];
        
        return view('admin.reports.overview', compact(
            'userMetrics',
            'jobMetrics',
            'bidMetrics',
            'paymentMetrics',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Display earnings report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function earnings(Request $request)
    {
        // Get date range
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        // Daily earnings
        $dailyEarnings = Payment::whereStatus('completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Category earnings
        $categoryEarnings = Payment::whereStatus('completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->join('jobs', 'payments.job_id', '=', 'jobs.id')
            ->join('categories', 'jobs.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(payments.amount) as total')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();
            
        return view('admin.reports.earnings', compact(
            'dailyEarnings',
            'categoryEarnings',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Display user analytics report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function users(Request $request)
    {
        // Get date range
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        
        // Daily signups
        $dailySignups = User::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // User roles distribution
        $roleDistribution = DB::table('role_user')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('roles.name', DB::raw('COUNT(*) as total'))
            ->groupBy('roles.id', 'roles.name')
            ->get();
            
        // Top earners
        $topEarners = User::join('payments', 'users.id', '=', 'payments.user_id')
            ->whereStatus('completed')
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->select(
                'users.id',
                'users.name',
                DB::raw('SUM(payments.amount) as total_earnings')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_earnings')
            ->limit(10)
            ->get();
            
        return view('admin.reports.users', compact(
            'dailySignups',
            'roleDistribution',
            'topEarners',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Display job analytics report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function jobs(Request $request)
    {
        // Get date range
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        
        // Daily job postings
        $dailyJobs = Job::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Category distribution
        $categoryDistribution = Job::join('categories', 'jobs.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();
            
        // Average bids per job
        $avgBidsPerJob = Job::whereBetween('created_at', [$startDate, $endDate])
            ->withCount('bids')
            ->get()
            ->avg('bids_count');
            
        // Top skills in demand
        $topSkills = DB::table('job_skill')
            ->join('skills', 'job_skill.skill_id', '=', 'skills.id')
            ->join('jobs', 'job_skill.job_id', '=', 'jobs.id')
            ->whereBetween('jobs.created_at', [$startDate, $endDate])
            ->select(
                'skills.name',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('skills.id', 'skills.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
            
        return view('admin.reports.jobs', compact(
            'dailyJobs',
            'categoryDistribution',
            'avgBidsPerJob',
            'topSkills',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Export report data.
     *
     * @param  string  $type
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export($type, Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        
        switch ($type) {
            case 'users':
                return $this->exportUsers($startDate, $endDate);
            case 'jobs':
                return $this->exportJobs($startDate, $endDate);
            case 'earnings':
                return $this->exportEarnings($startDate, $endDate);
            default:
                abort(404, 'Invalid report type');
        }
    }
    
    /**
     * Export users report.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    protected function exportUsers($startDate, $endDate)
    {
        $users = User::whereBetween('created_at', [$startDate, $endDate])
            ->with(['roles', 'skills'])
            ->get();
            
        return response()->streamDownload(function() use ($users) {
            $handle = fopen('php://output', 'w');
            
            // Headers
            fputcsv($handle, [
                'ID',
                'Name',
                'Email',
                'Status',
                'Roles',
                'Skills',
                'Created At'
            ]);
            
            // Data
            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->status,
                    $user->roles->pluck('name')->implode(', '),
                    $user->skills->pluck('name')->implode(', '),
                    $user->created_at
                ]);
            }
            
            fclose($handle);
        }, 'users_report.csv');
    }
    
    /**
     * Export jobs report.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    protected function exportJobs($startDate, $endDate)
    {
        $jobs = Job::whereBetween('created_at', [$startDate, $endDate])
            ->with(['category', 'skills', 'user'])
            ->get();
            
        return response()->streamDownload(function() use ($jobs) {
            $handle = fopen('php://output', 'w');
            
            // Headers
            fputcsv($handle, [
                'ID',
                'Title',
                'Category',
                'Skills',
                'Budget',
                'Status',
                'Posted By',
                'Created At'
            ]);
            
            // Data
            foreach ($jobs as $job) {
                fputcsv($handle, [
                    $job->id,
                    $job->title,
                    $job->category->name,
                    $job->skills->pluck('name')->implode(', '),
                    $job->budget,
                    $job->status,
                    $job->user->name,
                    $job->created_at
                ]);
            }
            
            fclose($handle);
        }, 'jobs_report.csv');
    }
    
    /**
     * Export earnings report.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    protected function exportEarnings($startDate, $endDate)
    {
        $payments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'job'])
            ->get();
            
        return response()->streamDownload(function() use ($payments) {
            $handle = fopen('php://output', 'w');
            
            // Headers
            fputcsv($handle, [
                'ID',
                'Amount',
                'Status',
                'Type',
                'User',
                'Job',
                'Created At'
            ]);
            
            // Data
            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->id,
                    $payment->amount,
                    $payment->status,
                    $payment->type,
                    $payment->user->name,
                    $payment->job ? $payment->job->title : 'N/A',
                    $payment->created_at
                ]);
            }
            
            fclose($handle);
        }, 'earnings_report.csv');
    }
}
