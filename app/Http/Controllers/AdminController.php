<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Job;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_jobs' => Job::count(),
            'total_payments' => Payment::sum('amount'),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_jobs' => Job::with('client')->latest()->take(5)->get(),
            'recent_payments' => Payment::with(['user', 'milestone'])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::withCount(['postedJobs', 'bids'])
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function jobs()
    {
        $jobs = Job::with(['client', 'freelancer'])
            ->withCount('bids')
            ->latest()
            ->paginate(10);

        return view('admin.jobs.index', compact('jobs'));
    }

    public function payments()
    {
        $payments = Payment::with(['user', 'milestone'])
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated successfully.');
    }

    public function verifyUser(User $user)
    {
        $user->update(['is_verified' => true]);
        return back()->with('success', 'User verified successfully.');
    }
} 