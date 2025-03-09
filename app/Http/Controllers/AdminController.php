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
        $query = Job::with(['user', 'skills', 'bids']);

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

        $jobs = $query->latest()->paginate(9); // Changed to 9 for 3x3 grid

        return view('admin.jobs.index', compact('jobs'));
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
        // Stats for cards
        $totalUsers = User::count();
        $activeJobs = Job::where('status', 'active')->count();
        $totalEarnings = Payment::where('status', 'completed')->sum('amount');
        $completedJobs = Job::where('status', 'completed')->count();

        // Get recent activities
        $recentJobs = Job::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($job) {
                return [
                    'user' => $job->user,
                    'description' => "New job posted: {$job->title}",
                    'created_at' => $job->created_at
                ];
            });

        $recentUsers = User::latest()
            ->take(5)
            ->get()
            ->map(function($user) {
                return [
                    'user' => $user,
                    'description' => "New user registered: {$user->name}",
                    'created_at' => $user->created_at
                ];
            });

        $recentPayments = Payment::with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($payment) {
                return [
                    'user' => $payment->user,
                    'description' => "Payment completed: ${$payment->amount}",
                    'created_at' => $payment->created_at
                ];
            });

        // Combine and sort activities
        $recentActivities = collect()
            ->merge($recentJobs)
            ->merge($recentUsers)
            ->merge($recentPayments)
            ->sortByDesc('created_at')
            ->take(10)
            ->map(function($activity) {
                return (object) $activity;
            })
            ->values();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeJobs',
            'totalEarnings',
            'completedJobs',
            'recentActivities'
        ));
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

    public function skillAssessments()
    {
        $assessments = SkillAssessment::with(['skill'])
            ->withCount(['attempts', 'attempts as passed_count' => function ($query) {
                $query->where('passed', true);
            }])
            ->latest()
            ->paginate(10);

        return view('admin.skill-assessments.index', compact('assessments'));
    }

    public function createSkillAssessment()
    {
        $skills = Skill::verified()->get();
        return view('admin.skill-assessments.create', compact('skills'));
    }

    public function storeSkillAssessment(Request $request)
    {
        $validated = $request->validate([
            'skill_id' => 'required|exists:skills,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced,expert',
            'time_limit' => 'required|integer|min:5|max:180',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.correct_answer' => 'required|integer|min:0',
            'questions.*.explanation' => 'required|string',
            'questions.*.category' => 'required|string'
        ]);

        $assessment = SkillAssessment::create($validated);

        return redirect()->route('admin.skill-assessments.index')
            ->with('success', 'Skill assessment created successfully.');
    }

    public function editSkillAssessment(SkillAssessment $assessment)
    {
        $skills = Skill::verified()->get();
        return view('admin.skill-assessments.edit', compact('assessment', 'skills'));
    }

    public function updateSkillAssessment(Request $request, SkillAssessment $assessment)
    {
        $validated = $request->validate([
            'skill_id' => 'required|exists:skills,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced,expert',
            'time_limit' => 'required|integer|min:5|max:180',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.correct_answer' => 'required|integer|min:0',
            'questions.*.explanation' => 'required|string',
            'questions.*.category' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $assessment->update($validated);

        return redirect()->route('admin.skill-assessments.index')
            ->with('success', 'Skill assessment updated successfully.');
    }

    public function deleteSkillAssessment(SkillAssessment $assessment)
    {
        $assessment->delete();

        return redirect()->route('admin.skill-assessments.index')
            ->with('success', 'Skill assessment deleted successfully.');
    }

    public function assessmentResults()
    {
        $attempts = SkillAssessmentAttempt::with(['user', 'assessment.skill'])
            ->latest()
            ->paginate(20);

        return view('admin.skill-assessments.results', compact('attempts'));
    }

    public function pendingFeedback()
    {
        $attempts = SkillAssessmentAttempt::with(['user', 'assessment.skill', 'feedback'])
            ->whereHas('feedback', function ($query) {
                $query->whereNull('feedback_date');
            })
            ->orWhereDoesntHave('feedback')
            ->where('completed_at', '<=', now()->subWeeks(2))
            ->latest()
            ->paginate(20);

        return view('admin.skill-assessments.pending-feedback', compact('attempts'));
    }

    public function showAttempt(SkillAssessmentAttempt $attempt)
    {
        return view('admin.skill-assessments.show-attempt', compact('attempt'));
    }

    public function createFeedback(SkillAssessmentAttempt $attempt)
    {
        return view('admin.skill-assessments.feedback', compact('attempt'));
    }

    public function provideFeedback(Request $request, SkillAssessmentAttempt $attempt)
    {
        $validated = $request->validate([
            'feedback' => 'required|string',
            'improvement_areas' => 'required|array',
            'recommended_resources' => 'required|array'
        ]);

        $feedback = $attempt->feedback()->updateOrCreate(
            ['skill_assessment_attempt_id' => $attempt->id],
            [
                'feedback' => $validated['feedback'],
                'improvement_areas' => $validated['improvement_areas'],
                'recommended_resources' => $validated['recommended_resources'],
                'feedback_date' => now(),
                'reviewed_by' => $request->user()->id
            ]
        );

        return redirect()->route('admin.skill-assessments.feedback')
            ->with('success', 'Feedback provided successfully.');
    }
} 