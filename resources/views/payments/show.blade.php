<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment for Milestone: ') . $milestone->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Payment Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Payment Details</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Amount</dt>
                                <dd class="mt-1 text-2xl font-semibold text-gray-900">${{ number_format($milestone->amount, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                        $milestone->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                    }}">
                                        {{ ucfirst($milestone->status) }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    @if($milestone->status !== 'paid')
                        <!-- Payment Methods -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Select Payment Method</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- M-Pesa -->
                                <div class="border rounded-lg p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-medium">M-Pesa</h4>
                                        <img src="{{ asset('images/mpesa-logo.png') }}" alt="M-Pesa" class="h-8">
                                    </div>
                                    <form id="mpesa-form" class="space-y-4">
                                        <div>
                                            <x-input-label for="phone" :value="__('Phone Number')" />
                                            <x-text-input id="phone" type="text" name="phone" class="mt-1 block w-full" required placeholder="254XXXXXXXXX" />
                                            <p class="mt-1 text-sm text-gray-500">Enter your M-Pesa registered phone number starting with 254</p>
                                        </div>
                                        <x-primary-button type="submit" class="w-full justify-center">
                                            {{ __('Pay with M-Pesa') }}
                                        </x-primary-button>
                                    </form>
                                </div>

                                <!-- PayPal -->
                                <div class="border rounded-lg p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-medium">PayPal</h4>
                                        <img src="{{ asset('images/paypal-logo.png') }}" alt="PayPal" class="h-8">
                                    </div>
                                    <form id="paypal-form" class="space-y-4">
                                        <p class="text-sm text-gray-500 mb-4">Pay securely using your PayPal account or credit card.</p>
                                        <x-primary-button type="submit" class="w-full justify-center">
                                            {{ __('Pay with PayPal') }}
                                        </x-primary-button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('mpesa-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch(`{{ route('payments.mpesa.process', $milestone) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        phone: document.getElementById('phone').value,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    // Poll for payment status
                    pollPaymentStatus(data.payment_id);
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            }
        });

        document.getElementById('paypal-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch(`{{ route('payments.paypal.process', $milestone) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            }
        });

        function pollPaymentStatus(paymentId) {
            const interval = setInterval(async () => {
                try {
                    const response = await fetch(`/payments/${paymentId}/status`);
                    const data = await response.json();

                    if (data.status === 'completed') {
                        clearInterval(interval);
                        window.location.reload();
                    } else if (data.status === 'failed') {
                        clearInterval(interval);
                        alert('Payment failed. Please try again.');
                    }
                } catch (error) {
                    clearInterval(interval);
                }
            }, 5000);
        }
    </script>
    @endpush
</x-app-layout> 