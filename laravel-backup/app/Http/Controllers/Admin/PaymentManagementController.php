<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Facades\Activity;

class PaymentManagementController extends Controller
{
    /**
     * Display a listing of payments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'job']);
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Get paginated results
        $payments = $query->latest()->paginate(20);
        
        // Get summary statistics
        $stats = [
            'total_amount' => Payment::whereStatus('completed')->sum('amount'),
            'pending_amount' => Payment::whereStatus('pending')->sum('amount'),
            'refunded_amount' => Payment::whereStatus('refunded')->sum('amount')
        ];
        
        return view('admin.payments.index', compact('payments', 'stats'));
    }
    
    /**
     * Display the specified payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\View\View
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'job']);
        
        // Get activity log
        $activities = DB::table('activity_log')
            ->where('subject_type', 'App\Models\Payment')
            ->where('subject_id', $payment->id)
            ->latest()
            ->take(50)
            ->get();
            
        return view('admin.payments.show', compact('payment', 'activities'));
    }
    
    /**
     * Update the payment status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded'
        ]);
        
        $payment->update([
            'status' => $request->status
        ]);
        
        // Log the action
        activity()
            ->performedOn($payment)
            ->withProperties(['status' => $request->status])
            ->log('updated payment status');
        
        return back()->with('success', 'Payment status updated successfully.');
    }
    
    /**
     * Process a refund for the payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function refund(Payment $payment)
    {
        // Check if payment can be refunded
        if ($payment->status !== 'completed') {
            return back()->with('error', 'Only completed payments can be refunded.');
        }
        
        try {
            DB::beginTransaction();
            
            // Update payment status
            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now()
            ]);
            
            // Create refund record
            $refund = $payment->refunds()->create([
                'amount' => $payment->amount,
                'reason' => 'Admin initiated refund',
                'status' => 'completed'
            ]);
            
            // Update user balance if necessary
            if ($payment->affects_balance) {
                $payment->user->decrement('balance', $payment->amount);
            }
            
            DB::commit();
            
            // Log the action
            activity()
                ->performedOn($payment)
                ->withProperties(['refund_id' => $refund->id])
                ->log('refunded payment');
            
            return back()->with('success', 'Payment refunded successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }
}
