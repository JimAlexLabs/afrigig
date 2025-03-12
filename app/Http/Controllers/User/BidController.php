<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    /**
     * Display a listing of the user's bids.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bids = Bid::where('user_id', Auth::id())
            ->with(['job', 'job.user'])
            ->latest()
            ->paginate(10);
            
        return view('user.bids.index', compact('bids'));
    }
    
    /**
     * Display the specified bid.
     *
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\View\View
     */
    public function show(Bid $bid)
    {
        // Ensure user owns the bid
        if ($bid->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $bid->load(['job', 'job.user', 'job.category', 'job.skills']);
        
        return view('user.bids.show', compact('bid'));
    }
    
    /**
     * Store a newly created bid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Job $job)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'timeline' => 'required|integer|min:1',
            'proposal' => 'required|string|min:50'
        ]);
        
        // Check if user already has a bid for this job
        $existingBid = Bid::where('user_id', Auth::id())
            ->where('job_id', $job->id)
            ->first();
            
        if ($existingBid) {
            return back()->with('error', 'You have already bid on this job.');
        }
        
        // Create new bid
        $bid = Bid::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'amount' => $request->amount,
            'timeline' => $request->timeline,
            'proposal' => $request->proposal,
            'status' => 'pending'
        ]);
        
        return redirect()->route('bids.show', $bid)
            ->with('success', 'Bid submitted successfully.');
    }
    
    /**
     * Update the specified bid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bid $bid)
    {
        // Ensure user owns the bid
        if ($bid->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if bid can be updated
        if ($bid->status !== 'pending') {
            return back()->with('error', 'This bid can no longer be updated.');
        }
        
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'timeline' => 'required|integer|min:1',
            'proposal' => 'required|string|min:50'
        ]);
        
        $bid->update([
            'amount' => $request->amount,
            'timeline' => $request->timeline,
            'proposal' => $request->proposal
        ]);
        
        return back()->with('success', 'Bid updated successfully.');
    }
    
    /**
     * Remove the specified bid.
     *
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bid $bid)
    {
        // Ensure user owns the bid
        if ($bid->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if bid can be deleted
        if ($bid->status !== 'pending') {
            return back()->with('error', 'This bid can no longer be withdrawn.');
        }
        
        $bid->delete();
        
        return redirect()->route('bids.index')
            ->with('success', 'Bid withdrawn successfully.');
    }
}
