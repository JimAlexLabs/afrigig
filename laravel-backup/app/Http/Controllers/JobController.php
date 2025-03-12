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

    /**
     * Display a listing of jobs.
     */
    public function browse()
    {
        $jobs = Job::where('active', true)
            ->latest()
            ->paginate(12);

        return view('jobs.browse', compact('jobs'));
    }

    /**
     * Display a listing of recommended jobs.
     */
    public function recommended()
    {
        $jobs = Job::where('active', true)
            ->latest()
            ->take(5)
            ->get();

        return view('jobs.recommended', compact('jobs'));
    }

    /**
     * Display a listing of jobs for the authenticated user
     */
    public function myJobs()
    {
        $user = Auth::user();
        
        // Get jobs where the user has placed bids
        $jobs = Job::whereHas('bids', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['user', 'bids' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->latest()->paginate(10);

        return view('jobs.my-jobs', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        if (Auth::user()->role !== 'client') {
            return redirect()->route('jobs.browse')
                ->with('error', 'Only clients can post jobs.');
        }

        return view('jobs.create');
    }

    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'client') {
            return redirect()->route('jobs.browse')
                ->with('error', 'Only clients can post jobs.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:0',
            'deadline' => 'required|date|after:today',
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $job = Job::create($validated + [
            'user_id' => Auth::id(),
            'active' => true,
        ]);

        $job->skills()->attach($request->skills);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job posted successfully.');
    }

    /**
     * Display the specified job.
     */
    public function show(Job $job)
    {
        $job->load(['bids.user']);
        return view('jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(Job $job)
    {
        $this->authorize('update', $job);
        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified job in storage.
     */
    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:0',
            'deadline' => 'required|date|after:today',
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $job->update($validated);
        $job->skills()->sync($request->skills);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified job from storage.
     */
    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);

        $job->delete();

        return redirect()->route('jobs.browse')
            ->with('success', 'Job deleted successfully.');
    }

    /**
     * Submit a bid for the specified job.
     */
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

    /**
     * Accept a bid for the specified job.
     */
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