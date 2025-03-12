<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Milestone $milestone)
    {
        $this->authorize('pay', $milestone);
        return view('payments.show', compact('milestone'));
    }

    public function processMpesa(Request $request, Milestone $milestone)
    {
        $this->authorize('pay', $milestone);

        $request->validate([
            'phone' => ['required', 'string', 'regex:/^254[0-9]{9}$/'],
        ]);

        try {
            // Initialize M-Pesa payment
            $response = Http::withBasicAuth(
                config('services.mpesa.key'),
                config('services.mpesa.secret')
            )->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => config('services.mpesa.shortcode'),
                'Password' => $this->generateMpesaPassword(),
                'Timestamp' => now()->format('YmdHis'),
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $milestone->amount,
                'PartyA' => $request->phone,
                'PartyB' => config('services.mpesa.shortcode'),
                'PhoneNumber' => $request->phone,
                'CallBackURL' => route('payments.mpesa.callback'),
                'AccountReference' => 'Afrigig-' . $milestone->id,
                'TransactionDesc' => 'Payment for milestone: ' . $milestone->title,
            ]);

            if ($response->successful()) {
                $payment = Payment::create([
                    'user_id' => auth()->id(),
                    'milestone_id' => $milestone->id,
                    'amount' => $milestone->amount,
                    'payment_method' => 'mpesa',
                    'transaction_id' => Str::random(20),
                    'status' => 'pending',
                    'payment_details' => $response->json(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment initiated. Please check your phone to complete the transaction.',
                    'payment_id' => $payment->id,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate payment. Please try again.',
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }

    public function processPaypal(Request $request, Milestone $milestone)
    {
        $this->authorize('pay', $milestone);

        try {
            // Initialize PayPal payment
            $response = Http::withBasicAuth(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($milestone->amount, 2, '.', ''),
                    ],
                    'description' => 'Payment for milestone: ' . $milestone->title,
                ]],
                'application_context' => [
                    'return_url' => route('payments.paypal.success'),
                    'cancel_url' => route('payments.paypal.cancel'),
                ],
            ]);

            if ($response->successful()) {
                $payment = Payment::create([
                    'user_id' => auth()->id(),
                    'milestone_id' => $milestone->id,
                    'amount' => $milestone->amount,
                    'payment_method' => 'paypal',
                    'transaction_id' => Str::random(20),
                    'status' => 'pending',
                    'payment_details' => $response->json(),
                ]);

                return response()->json([
                    'success' => true,
                    'redirect_url' => collect($response->json()['links'])
                        ->firstWhere('rel', 'approve')['href'],
                    'payment_id' => $payment->id,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate payment. Please try again.',
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }

    public function mpesaCallback(Request $request)
    {
        // Handle M-Pesa callback
        $callback = $request->all();
        
        if (isset($callback['Body']['stkCallback'])) {
            $result = $callback['Body']['stkCallback'];
            $payment = Payment::where('payment_details->CheckoutRequestID', $result['CheckoutRequestID'])->first();

            if ($payment) {
                if ($result['ResultCode'] == 0) {
                    $payment->update([
                        'status' => 'completed',
                        'payment_details' => array_merge(
                            $payment->payment_details,
                            ['callback_result' => $result]
                        ),
                    ]);

                    // Update milestone status
                    $payment->milestone->update(['status' => 'paid']);
                } else {
                    $payment->update([
                        'status' => 'failed',
                        'payment_details' => array_merge(
                            $payment->payment_details,
                            ['callback_result' => $result]
                        ),
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function paypalSuccess(Request $request)
    {
        $payment = Payment::find($request->payment_id);
        
        if ($payment) {
            try {
                $response = Http::withBasicAuth(
                    config('services.paypal.client_id'),
                    config('services.paypal.secret')
                )->post("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$request->token}/capture");

                if ($response->successful()) {
                    $payment->update([
                        'status' => 'completed',
                        'payment_details' => array_merge(
                            $payment->payment_details,
                            ['capture_result' => $response->json()]
                        ),
                    ]);

                    // Update milestone status
                    $payment->milestone->update(['status' => 'paid']);

                    return redirect()->route('payments.show', $payment->milestone)
                        ->with('success', 'Payment completed successfully.');
                }
            } catch (\Exception $e) {
                $payment->update(['status' => 'failed']);
            }
        }

        return redirect()->route('payments.show', $payment->milestone)
            ->with('error', 'Payment failed. Please try again.');
    }

    public function paypalCancel(Request $request)
    {
        $payment = Payment::find($request->payment_id);
        
        if ($payment) {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('payments.show', $payment->milestone)
            ->with('error', 'Payment cancelled.');
    }

    protected function generateMpesaPassword()
    {
        $timestamp = now()->format('YmdHis');
        return base64_encode(
            config('services.mpesa.shortcode') .
            config('services.mpesa.passkey') .
            $timestamp
        );
    }
} 