<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['job', 'job.skills'])
            ->get();
            
        $total = $cartItems->sum('amount');
        
        return view('user.cart.index', compact('cartItems', 'total'));
    }
    
    /**
     * Add a job to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, Job $job)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'timeline' => 'required|integer|min:1',
            'proposal' => 'required|string|min:50'
        ]);
        
        // Check if job is already in cart
        $existingItem = Cart::where('user_id', Auth::id())
            ->where('job_id', $job->id)
            ->first();
            
        if ($existingItem) {
            return back()->with('error', 'This job is already in your cart.');
        }
        
        // Create new cart item
        Cart::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'amount' => $request->amount,
            'timeline' => $request->timeline,
            'proposal' => $request->proposal
        ]);
        
        return back()->with('success', 'Job added to cart successfully.');
    }
    
    /**
     * Update a cart item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $item)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'timeline' => 'required|integer|min:1',
            'proposal' => 'required|string|min:50'
        ]);
        
        // Ensure user owns the cart item
        if ($item->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        $item->update([
            'amount' => $request->amount,
            'timeline' => $request->timeline,
            'proposal' => $request->proposal
        ]);
        
        return back()->with('success', 'Cart item updated successfully.');
    }
    
    /**
     * Remove a cart item.
     *
     * @param  \App\Models\Cart  $item
     * @return \Illuminate\Http\Response
     */
    public function remove(Cart $item)
    {
        // Ensure user owns the cart item
        if ($item->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        $item->delete();
        
        return back()->with('success', 'Item removed from cart.');
    }
    
    /**
     * Process checkout for all items in cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('job')
            ->get();
            
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }
        
        // Process each item in cart
        foreach ($cartItems as $item) {
            // Create bid for each job
            $item->job->bids()->create([
                'user_id' => Auth::id(),
                'amount' => $item->amount,
                'timeline' => $item->timeline,
                'proposal' => $item->proposal,
                'status' => 'pending'
            ]);
            
            // Remove item from cart
            $item->delete();
        }
        
        return redirect()->route('bids.index')
            ->with('success', 'Bids submitted successfully for all items in cart.');
    }
}
