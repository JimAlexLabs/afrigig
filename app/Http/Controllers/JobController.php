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

    public function index(Request $request)
    {
        $query = Job::with(['client', 'bids'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->when($request->experience_level, function ($query, $level) {
                $query->where('experience_level', $level);
            })
            ->when($request->budget_min, function ($query, $min) {
                $query->where('budget_min', '>=', $min);
            })
            ->when($request->budget_max, function ($query, $max) {
                $query->where('budget_max', '<=', $max);
            });

        $jobs = $query->latest()->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        $this->authorize('create', Job::class);
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Job::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'skills_required' => 'required|array',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|gt:budget_min',
            'deadline' => 'required|date|after:today',
            'experience_level' => 'required|in:entry,intermediate,expert',
            'project_length' => 'required|in:short,medium,long',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('job-attachments', 'public');
                $attachments[] = $path;
            }
        }

        $job = Job::create([
            'client_id' => Auth::id(),
            'attachments' => $attachments,
        ] + $validated);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job posted successfully.');
    }

    public function show(Job $job)
    {
        $job->load(['client', 'bids.freelancer', 'milestones']);
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $this->authorize('update', $job);
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'skills_required' => 'required|array',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|gt:budget_min',
            'deadline' => 'required|date|after:today',
            'experience_level' => 'required|in:entry,intermediate,expert',
            'project_length' => 'required|in:short,medium,long',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $attachments = $job->attachments;
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('job-attachments', 'public');
                $attachments[] = $path;
            }
        }

        $job->update([
            'attachments' => $attachments,
        ] + $validated);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);

        // Delete attachments
        foreach ($job->attachments as $attachment) {
            Storage::disk('public')->delete($attachment);
        }

        $job->delete();

        return redirect()->route('jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    public function submitBid(Request $request, Job $job)
    {
        $this->authorize('bid', $job);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:' . $job->budget_min . '|max:' . $job->budget_max,
            'proposal' => 'required|string|min:100',
            'delivery_time' => 'required|integer|min:1',
        ]);

        $bid = new Bid([
            'freelancer_id' => Auth::id(),
        ] + $validated);

        $job->bids()->save($bid);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Bid submitted successfully.');
    }

    public function acceptBid(Job $job, Bid $bid)
    {
        $this->authorize('update', $job);

        $bid->update(['status' => 'accepted']);
        $job->update([
            'status' => 'in_progress',
            'freelancer_id' => $bid->freelancer_id
        ]);

        // Reject other bids
        $job->bids()->where('id', '!=', $bid->id)->update(['status' => 'rejected']);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Bid accepted successfully.');
    }
} 