<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $jobs = Job::latest()->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    public function available()
    {
        $jobs = Job::where('status', 'open')
            ->orderBy('deadline')
            ->take(5)
            ->get();
        
        return view('jobs.available', compact('jobs'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'client') {
            return redirect()->route('jobs.index')
                ->with('error', 'Only clients can post jobs.');
        }

        return view('jobs.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'client') {
            return redirect()->route('jobs.index')
                ->with('error', 'Only clients can post jobs.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:today',
            'skills' => 'required|array|min:1',
            'skills.*' => 'string',
        ]);

        $job = Auth::user()->jobs()->create($validated);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job posted successfully.');
    }

    public function show(Job $job)
    {
        $job->load(['bids.user']);
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        if (Auth::id() !== $job->user_id) {
            return redirect()->route('jobs.index')
                ->with('error', 'You can only edit your own jobs.');
        }

        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        if (Auth::id() !== $job->user_id) {
            return redirect()->route('jobs.index')
                ->with('error', 'You can only edit your own jobs.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:today',
            'skills' => 'required|array|min:1',
            'skills.*' => 'string',
        ]);

        $job->update($validated);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        if (Auth::id() !== $job->user_id) {
            return redirect()->route('jobs.index')
                ->with('error', 'You can only delete your own jobs.');
        }

        $job->delete();

        return redirect()->route('jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    public function submitBid(Request $request, Job $job)
    {
        if (Auth::user()->role !== 'freelancer') {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'Only freelancers can submit bids.');
        }

        if ($job->bids()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'You have already bid on this job.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'proposal' => 'required|string',
            'delivery_time' => 'required|integer|min:1',
        ]);

        $job->bids()->create([
            'user_id' => Auth::id(),
            'amount' => $validated['amount'],
            'proposal' => $validated['proposal'],
            'delivery_time' => $validated['delivery_time'],
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Bid submitted successfully.');
    }

    public function acceptBid(Job $job, Bid $bid)
    {
        if (Auth::id() !== $job->user_id) {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'Only the job owner can accept bids.');
        }

        if ($job->status !== 'active') {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'This job is no longer active.');
        }

        $bid->update(['status' => 'accepted']);
        $job->update(['status' => 'in_progress']);

        // Create milestone
        $job->milestones()->create([
            'title' => 'Project Completion',
            'description' => 'Complete project delivery',
            'amount' => $bid->amount,
            'due_date' => now()->addDays($bid->delivery_time),
            'freelancer_id' => $bid->user_id,
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Bid accepted successfully.');
    }
} 