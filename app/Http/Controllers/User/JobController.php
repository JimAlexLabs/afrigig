<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    /**
     * Display a listing of public jobs.
     */
    public function public()
    {
        $jobs = Job::where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('jobs.public', compact('jobs'));
    }
    
    /**
     * Display a listing of jobs for browsing.
     */
    public function browse()
    {
        $jobs = Job::where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('jobs.browse', compact('jobs'));
    }
    
    /**
     * Display a listing of recommended jobs.
     */
    public function recommended()
    {
        // In a real app, this would use user preferences and skills to recommend jobs
        $jobs = Job::where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('jobs.recommended', compact('jobs'));
    }
    
    /**
     * Display a listing of the user's jobs.
     */
    public function myJobs()
    {
        $jobs = Job::where('client_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('jobs.my-jobs', compact('jobs'));
    }
    
    /**
     * Show the form for creating a new job.
     */
    public function create()
    {
        return view('jobs.create');
    }
    
    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'skills_required' => 'required|string',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|gte:budget_min',
            'deadline' => 'required|date|after:today',
            'experience_level' => 'required|string',
            'project_length' => 'required|string',
        ]);
        
        $job = new Job($validated);
        $job->client_id = auth()->id();
        $job->status = 'open';
        $job->save();
        
        return redirect()->route('jobs.show', $job)->with('success', 'Job created successfully!');
    }
    
    /**
     * Display the specified job.
     */
    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }
    
    /**
     * Show the form for editing the specified job.
     */
    public function edit(Job $job)
    {
        // Check if the user is the owner of the job
        if ($job->client_id !== auth()->id()) {
            return redirect()->route('jobs.show', $job)->with('error', 'You are not authorized to edit this job.');
        }
        
        return view('jobs.edit', compact('job'));
    }
    
    /**
     * Update the specified job in storage.
     */
    public function update(Request $request, Job $job)
    {
        // Check if the user is the owner of the job
        if ($job->client_id !== auth()->id()) {
            return redirect()->route('jobs.show', $job)->with('error', 'You are not authorized to update this job.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'skills_required' => 'required|string',
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|gte:budget_min',
            'deadline' => 'required|date|after:today',
            'experience_level' => 'required|string',
            'project_length' => 'required|string',
        ]);
        
        $job->update($validated);
        
        return redirect()->route('jobs.show', $job)->with('success', 'Job updated successfully!');
    }
    
    /**
     * Remove the specified job from storage.
     */
    public function destroy(Job $job)
    {
        // Check if the user is the owner of the job
        if ($job->client_id !== auth()->id()) {
            return redirect()->route('jobs.show', $job)->with('error', 'You are not authorized to delete this job.');
        }
        
        $job->delete();
        
        return redirect()->route('jobs.my-jobs')->with('success', 'Job deleted successfully!');
    }
    
    /**
     * Submit a bid for a job.
     */
    public function submitBid(Request $request, Job $job)
    {
        // Check if the user is not the owner of the job
        if ($job->client_id === auth()->id()) {
            return redirect()->route('jobs.show', $job)->with('error', 'You cannot bid on your own job.');
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'proposal' => 'required|string',
            'delivery_time' => 'required|integer|min:1',
        ]);
        
        $bid = $job->bids()->create([
            'freelancer_id' => auth()->id(),
            'amount' => $validated['amount'],
            'proposal' => $validated['proposal'],
            'delivery_time' => $validated['delivery_time'],
            'status' => 'pending',
        ]);
        
        return redirect()->route('jobs.show', $job)->with('success', 'Bid submitted successfully!');
    }
    
    /**
     * Accept a bid for a job.
     */
    public function acceptBid(Job $job, $bidId)
    {
        // Check if the user is the owner of the job
        if ($job->client_id !== auth()->id()) {
            return redirect()->route('jobs.show', $job)->with('error', 'You are not authorized to accept bids for this job.');
        }
        
        $bid = $job->bids()->findOrFail($bidId);
        $bid->status = 'accepted';
        $bid->save();
        
        // Update job status
        $job->status = 'in_progress';
        $job->freelancer_id = $bid->freelancer_id;
        $job->save();
        
        // Reject other bids
        $job->bids()->where('id', '!=', $bidId)->update(['status' => 'rejected']);
        
        return redirect()->route('jobs.show', $job)->with('success', 'Bid accepted successfully!');
    }
}
