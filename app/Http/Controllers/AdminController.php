<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Bid;
use App\Models\SkillAssessment;
use App\Models\Skill;
use App\Models\SkillAssessmentAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function users()
    {
        $users = User::withCount(['jobs', 'assessments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function jobs()
    {
        $jobs = Job::with(['user', 'bids'])
            ->withCount('bids')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.jobs.index', compact('jobs'));
    }

    public function payments()
    {
        $payments = Payment::with(['user', 'job'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    public function verifyUser(User $user)
    {
        $user->update(['email_verified_at' => now()]);
        return back()->with('success', 'User verified successfully');
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated successfully');
    }

    public function showUser(User $user)
    {
        $user->load(['jobs', 'assessments', 'payments']);
        return view('admin.users.show', compact('user'));
    }

    public function deleteJob(Job $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully');
    }

    public function showJob(Job $job)
    {
        $job->load(['user', 'bids.user']);
        return view('admin.jobs.show', compact('job'));
    }

    public function dashboard()
    {
        // Get the start of the current month
        $monthStart = Carbon::now()->startOfMonth();
        
        $data = [
            'totalUsers' => User::count(),
            'activeJobs' => Job::where('status', 'open')->count(),
            'pendingAssessments' => SkillAssessment::where('status', 'pending')->count(),
            'totalEarnings' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $monthStart)
                ->sum('amount'),
            'recentJobs' => Job::with(['user'])
                ->latest()
                ->take(5)
                ->get(),
            'recentAssessments' => SkillAssessment::with(['user'])
                ->latest()
                ->take(5)
                ->get(),
            'monthlyStats' => [
                'userGrowth' => $this->calculateGrowthPercentage(User::class, $monthStart),
                'jobGrowth' => $this->calculateGrowthPercentage(Job::class, $monthStart),
                'earningsGrowth' => $this->calculateEarningsGrowth($monthStart),
            ],
        ];

        return view('admin.dashboard', $data);
    }

    private function calculateGrowthPercentage($model, $monthStart)
    {
        $currentCount = $model::where('created_at', '>=', $monthStart)->count();
        $lastMonthCount = $model::where('created_at', '>=', $monthStart->copy()->subMonth())
            ->where('created_at', '<', $monthStart)
            ->count();

        if ($lastMonthCount === 0) {
            return $currentCount > 0 ? 100 : 0;
        }

        return round((($currentCount - $lastMonthCount) / $lastMonthCount) * 100, 1);
    }

    private function calculateEarningsGrowth($monthStart)
    {
        $currentEarnings = Payment::where('status', 'completed')
            ->where('created_at', '>=', $monthStart)
            ->sum('amount');

        $lastMonthEarnings = Payment::where('status', 'completed')
            ->where('created_at', '>=', $monthStart->copy()->subMonth())
            ->where('created_at', '<', $monthStart)
            ->sum('amount');

        if ($lastMonthEarnings === 0) {
            return $currentEarnings > 0 ? 100 : 0;
        }

        return round((($currentEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100, 1);
    }

    public function showPayment(Payment $payment)
    {
        $payment->load(['user', 'job']);
        return view('admin.payments.show', compact('payment'));
    }

    public function processPayment(Payment $payment)
    {
        $payment->update(['status' => 'completed']);
        return back()->with('success', 'Payment processed successfully');
    }

    public function refundPayment(Payment $payment)
    {
        $payment->update(['status' => 'refunded']);
        return back()->with('success', 'Payment refunded successfully');
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

    public function skillAssessments()
    {
        $assessments = SkillAssessment::withCount(['attempts', 'completedAttempts'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.skill-assessments.index', compact('assessments'));
    }

    public function createSkillAssessment()
    {
        return view('admin.skill-assessments.create');
    }

    public function storeSkillAssessment(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'difficulty_level' => 'required|string',
            'time_limit' => 'required|integer|min:5',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.content' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
            'questions.*.correct_answer' => 'required|string',
        ]);

        $assessment = SkillAssessment::create($validated);

        return redirect()->route('admin.skill-assessments.show', $assessment)
            ->with('success', 'Skill assessment created successfully');
    }

    public function editSkillAssessment(SkillAssessment $assessment)
    {
        return view('admin.skill-assessments.edit', compact('assessment'));
    }

    public function updateSkillAssessment(Request $request, SkillAssessment $assessment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'difficulty_level' => 'required|string',
            'time_limit' => 'required|integer|min:5',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.content' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
            'questions.*.correct_answer' => 'required|string',
        ]);

        $assessment->update($validated);

        return redirect()->route('admin.skill-assessments.show', $assessment)
            ->with('success', 'Skill assessment updated successfully');
    }

    public function deleteSkillAssessment(SkillAssessment $assessment)
    {
        $assessment->delete();
        return redirect()->route('admin.skill-assessments.index')
            ->with('success', 'Skill assessment deleted successfully');
    }

    public function assessmentResults()
    {
        $results = DB::table('skill_assessment_attempts')
            ->join('users', 'skill_assessment_attempts.user_id', '=', 'users.id')
            ->join('skill_assessments', 'skill_assessment_attempts.assessment_id', '=', 'skill_assessments.id')
            ->select('skill_assessment_attempts.*', 'users.name as user_name', 'skill_assessments.title as assessment_title')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.skill-assessments.results', compact('results'));
    }

    public function pendingFeedback()
    {
        $attempts = DB::table('skill_assessment_attempts')
            ->where('status', 'completed')
            ->whereNull('feedback')
            ->join('users', 'skill_assessment_attempts.user_id', '=', 'users.id')
            ->join('skill_assessments', 'skill_assessment_attempts.assessment_id', '=', 'skill_assessments.id')
            ->select('skill_assessment_attempts.*', 'users.name as user_name', 'skill_assessments.title as assessment_title')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.skill-assessments.pending-feedback', compact('attempts'));
    }

    public function showAttempt($attempt)
    {
        $attempt = DB::table('skill_assessment_attempts')
            ->where('skill_assessment_attempts.id', $attempt)
            ->join('users', 'skill_assessment_attempts.user_id', '=', 'users.id')
            ->join('skill_assessments', 'skill_assessment_attempts.assessment_id', '=', 'skill_assessments.id')
            ->select('skill_assessment_attempts.*', 'users.name as user_name', 'skill_assessments.title as assessment_title')
            ->first();

        return view('admin.skill-assessments.attempt', compact('attempt'));
    }

    public function createFeedback($attempt)
    {
        $attempt = DB::table('skill_assessment_attempts')
            ->where('skill_assessment_attempts.id', $attempt)
            ->join('users', 'skill_assessment_attempts.user_id', '=', 'users.id')
            ->join('skill_assessments', 'skill_assessment_attempts.assessment_id', '=', 'skill_assessments.id')
            ->select('skill_assessment_attempts.*', 'users.name as user_name', 'skill_assessments.title as assessment_title')
            ->first();

        return view('admin.skill-assessments.feedback', compact('attempt'));
    }

    public function provideFeedback(Request $request, $attempt)
    {
        $validated = $request->validate([
            'feedback' => 'required|string',
            'score' => 'required|integer|min:0|max:100',
        ]);

        DB::table('skill_assessment_attempts')
            ->where('id', $attempt)
            ->update([
                'feedback' => $validated['feedback'],
                'score' => $validated['score'],
                'feedback_provided_at' => now(),
            ]);

        return redirect()->route('admin.skill-assessments.attempt', $attempt)
            ->with('success', 'Feedback provided successfully');
    }

    public function createJob()
    {
        return view('admin.jobs.create');
    }

    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'required|string',
            'category' => 'required|string',
            'skills_required' => 'required|array',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|gt:budget_min',
            'deadline' => 'required|date|after:today',
            'experience_level' => 'required|string',
            'project_length' => 'required|string',
        ]);

        $job = Job::create($validated + [
            'status' => 'open',
            'posted_by' => Auth::user()->name,
        ]);

        return redirect()->route('admin.jobs.show', $job)
            ->with('success', 'Job created successfully');
    }

    public function editJob(Job $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function updateJob(Request $request, Job $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'required|string',
            'category' => 'required|string',
            'skills_required' => 'required|array',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|gt:budget_min',
            'deadline' => 'required|date|after:today',
            'experience_level' => 'required|string',
            'project_length' => 'required|string',
            'status' => 'required|string',
        ]);

        $job->update($validated);

        return redirect()->route('admin.jobs.show', $job)
            ->with('success', 'Job updated successfully');
    }
} 