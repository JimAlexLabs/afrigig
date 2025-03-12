<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Job $job)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'proposal' => 'required|string|min:50',
            'delivery_time' => 'required|integer|min:1',
        ]);

        $bid = $job->bids()->create([
            'user_id' => Auth::user()->id,
            'amount' => $validated['amount'],
            'proposal' => $validated['proposal'],
            'delivery_time' => $validated['delivery_time'],
            'status' => 'pending'
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Your bid has been submitted successfully.');
    }

    public function update(Request $request, Bid $bid)
    {
        $this->authorize('update', $bid);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'proposal' => 'required|string|min:50',
            'delivery_time' => 'required|integer|min:1',
        ]);

        $bid->update($validated);

        return redirect()->route('jobs.show', $bid->job)
            ->with('success', 'Your bid has been updated successfully.');
    }

    public function destroy(Bid $bid)
    {
        $this->authorize('delete', $bid);

        $bid->delete();

        return redirect()->route('jobs.show', $bid->job)
            ->with('success', 'Your bid has been withdrawn successfully.');
    }

    public function accept(Bid $bid)
    {
        $this->authorize('accept', $bid);

        $bid->update(['status' => 'accepted']);
        $bid->job->update(['status' => 'in_progress']);
        
        // Reject all other bids
        $bid->job->bids()
            ->where('id', '!=', $bid->id)
            ->update(['status' => 'rejected']);

        return redirect()->route('jobs.show', $bid->job)
            ->with('success', 'Bid accepted successfully.');
    }

    public function reject(Bid $bid)
    {
        $this->authorize('reject', $bid);

        $bid->update(['status' => 'rejected']);

        return redirect()->route('jobs.show', $bid->job)
            ->with('success', 'Bid rejected successfully.');
    }
} 