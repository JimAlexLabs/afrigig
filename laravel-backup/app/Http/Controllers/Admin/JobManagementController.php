<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Category;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Facades\Activity;

class JobManagementController extends Controller
{
    /**
     * Display a listing of jobs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Job::with(['category', 'skills', 'user', 'bids']);
        
        // Apply filters
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        
        // Get paginated results
        $jobs = $query->latest()->paginate(20);
        
        // Get categories for filter
        $categories = Category::all();
        
        return view('admin.jobs.index', compact('jobs', 'categories'));
    }
    
    /**
     * Show the form for creating a new job.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        $skills = Skill::all();
        
        return view('admin.jobs.create', compact('categories', 'skills'));
    }
    
    /**
     * Store a newly created job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id',
            'budget' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:today',
            'attachments.*' => 'file|max:10240'
        ]);
        
        // Create job
        $job = Job::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'budget' => $request->budget,
            'deadline' => $request->deadline,
            'status' => 'active'
        ]);
        
        // Attach skills
        $job->skills()->attach($request->skills);
        
        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('job-attachments');
                $job->attachments()->create([
                    'path' => $path,
                    'name' => $file->getClientOriginalName()
                ]);
            }
        }
        
        // Log the action
        activity()
            ->performedOn($job)
            ->log('created job');
        
        return redirect()->route('admin.jobs.show', $job)
            ->with('success', 'Job created successfully.');
    }
    
    /**
     * Display the specified job.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\View\View
     */
    public function show(Job $job)
    {
        $job->load([
            'category',
            'skills',
            'user',
            'bids.user',
            'attachments'
        ]);
        
        // Get activity log
        $activities = DB::table('activity_log')
            ->where('subject_type', 'App\Models\Job')
            ->where('subject_id', $job->id)
            ->latest()
            ->take(50)
            ->get();
            
        return view('admin.jobs.show', compact('job', 'activities'));
    }
    
    /**
     * Show the form for editing the specified job.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\View\View
     */
    public function edit(Job $job)
    {
        $job->load(['category', 'skills', 'attachments']);
        
        $categories = Category::all();
        $skills = Skill::all();
        
        return view('admin.jobs.edit', compact('job', 'categories', 'skills'));
    }
    
    /**
     * Update the specified job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id',
            'budget' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:today',
            'status' => 'required|in:active,completed,cancelled',
            'attachments.*' => 'file|max:10240'
        ]);
        
        // Update job
        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'budget' => $request->budget,
            'deadline' => $request->deadline,
            'status' => $request->status
        ]);
        
        // Sync skills
        $job->skills()->sync($request->skills);
        
        // Handle new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('job-attachments');
                $job->attachments()->create([
                    'path' => $path,
                    'name' => $file->getClientOriginalName()
                ]);
            }
        }
        
        // Handle deleted attachments
        if ($request->has('delete_attachments')) {
            foreach ($request->delete_attachments as $attachmentId) {
                $attachment = $job->attachments()->find($attachmentId);
                if ($attachment) {
                    Storage::delete($attachment->path);
                    $attachment->delete();
                }
            }
        }
        
        // Log the action
        activity()
            ->performedOn($job)
            ->withProperties(['status' => $request->status])
            ->log('updated job');
        
        return redirect()->route('admin.jobs.show', $job)
            ->with('success', 'Job updated successfully.');
    }
    
    /**
     * Remove the specified job.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        // Delete attachments
        foreach ($job->attachments as $attachment) {
            Storage::delete($attachment->path);
        }
        
        // Log the action before deletion
        activity()
            ->performedOn($job)
            ->log('deleted job');
            
        $job->delete();
        
        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }
}
