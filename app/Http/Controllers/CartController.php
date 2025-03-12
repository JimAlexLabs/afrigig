<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display the user's cart
     */
    public function index()
    {
        $user = Auth::user();
        $cartItems = $user->cart()
            ->with('job.user')
            ->latest()
            ->get();

        $totalPremiumCost = $cartItems->sum('premium_cost');

        return view('cart.index', compact('cartItems', 'totalPremiumCost'));
    }

    /**
     * Add a job to the cart
     */
    public function add(Request $request, Job $job)
    {
        $user = Auth::user();

        // Check if user has passed skill assessment
        if (!$user->hasPassedSkillAssessment()) {
            return redirect()->route('skill-assessments.index')
                ->with('error', 'You need to pass a skill assessment before bidding on jobs.');
        }

        // Validate the request
        $validated = $request->validate([
            'bid_amount' => 'required|numeric|min:1',
            'timeline' => 'required|integer|min:1',
            'description' => 'required|string|min:50',
            'hide_bid' => 'boolean',
            'featured_bid' => 'boolean'
        ]);

        // Check if job is already in cart
        if ($user->cart()->where('job_id', $job->id)->exists()) {
            return back()->with('error', 'This job is already in your cart.');
        }

        // Create cart item
        $cartItem = $user->cart()->create([
            'job_id' => $job->id,
            'bid_amount' => $validated['bid_amount'],
            'timeline' => $validated['timeline'],
            'description' => $validated['description'],
            'hide_bid' => $validated['hide_bid'] ?? false,
            'featured_bid' => $validated['featured_bid'] ?? false
        ]);

        // Calculate premium cost
        $cartItem->calculateTotalCost();

        // Check if user has sufficient balance for premium features
        if ($cartItem->hasPremiumFeatures() && !$user->hasSufficientBalance($cartItem->premium_cost)) {
            $cartItem->delete();
            return back()->with('error', 'Insufficient balance for premium features.');
        }

        return redirect()->route('cart.index')
            ->with('success', 'Job added to cart successfully.');
    }

    /**
     * Update a cart item
     */
    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        // Validate the request
        $validated = $request->validate([
            'bid_amount' => 'required|numeric|min:1',
            'timeline' => 'required|integer|min:1',
            'description' => 'required|string|min:50',
            'hide_bid' => 'boolean',
            'featured_bid' => 'boolean'
        ]);

        // Update cart item
        $cart->update([
            'bid_amount' => $validated['bid_amount'],
            'timeline' => $validated['timeline'],
            'description' => $validated['description'],
            'hide_bid' => $validated['hide_bid'] ?? false,
            'featured_bid' => $validated['featured_bid'] ?? false
        ]);

        // Recalculate premium cost
        $cart->calculateTotalCost();

        // Check if user has sufficient balance for premium features
        if ($cart->hasPremiumFeatures() && !$cart->user->hasSufficientBalance($cart->premium_cost)) {
            $cart->update([
                'hide_bid' => false,
                'featured_bid' => false
            ]);
            $cart->calculateTotalCost();
            return back()->with('error', 'Premium features disabled due to insufficient balance.');
        }

        return back()->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove a cart item
     */
    public function remove(Cart $cart)
    {
        $this->authorize('delete', $cart);

        $cart->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Toggle a premium feature for a cart item
     */
    public function toggleFeature(Request $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        $validated = $request->validate([
            'feature' => 'required|string|in:hide_bid,featured_bid'
        ]);

        try {
            $cart->togglePremiumFeature($validated['feature']);

            // Check if user has sufficient balance
            if ($cart->hasPremiumFeatures() && !$cart->user->hasSufficientBalance($cart->premium_cost)) {
                $cart->update([
                    $validated['feature'] => false
                ]);
                $cart->calculateTotalCost();
                return back()->with('error', 'Insufficient balance for premium feature.');
            }

            return back()->with('success', 'Premium feature updated successfully.');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
} 