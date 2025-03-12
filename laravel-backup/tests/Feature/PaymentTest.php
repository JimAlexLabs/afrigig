<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Job;
use App\Models\Bid;
use App\Models\Milestone;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake([
            'sandbox.safaricom.co.ke/*' => Http::response([
                'CheckoutRequestID' => 'ws_CO_123456789',
                'ResponseCode' => '0',
                'ResponseDescription' => 'Success',
            ], 200),
            'api-m.sandbox.paypal.com/*' => Http::response([
                'id' => 'ORDER_123456789',
                'status' => 'CREATED',
                'links' => [
                    ['rel' => 'approve', 'href' => 'https://sandbox.paypal.com/approve'],
                ],
            ], 200),
        ]);
    }

    public function test_client_can_view_payment_page()
    {
        $client = User::factory()->create(['role' => 'client']);
        $job = Job::factory()->create(['client_id' => $client->id]);
        $milestone = Milestone::factory()->create(['job_id' => $job->id]);

        $response = $this->actingAs($client)->get("/payments/{$milestone->id}");

        $response->assertOk()
            ->assertViewIs('payments.show')
            ->assertViewHas('milestone');
    }

    public function test_freelancer_cannot_view_payment_page()
    {
        $client = User::factory()->create(['role' => 'client']);
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $job = Job::factory()->create(['client_id' => $client->id]);
        $milestone = Milestone::factory()->create(['job_id' => $job->id]);

        $response = $this->actingAs($freelancer)->get("/payments/{$milestone->id}");

        $response->assertForbidden();
    }

    public function test_client_can_initiate_mpesa_payment()
    {
        $client = User::factory()->create(['role' => 'client']);
        $job = Job::factory()->create(['client_id' => $client->id]);
        $milestone = Milestone::factory()->create(['job_id' => $job->id]);

        $response = $this->actingAs($client)->postJson("/payments/{$milestone->id}/mpesa", [
            'phone' => '254712345678',
        ]);

        $response->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonStructure(['payment_id']);

        $this->assertDatabaseHas('payments', [
            'milestone_id' => $milestone->id,
            'payment_method' => 'mpesa',
            'status' => 'pending',
        ]);
    }

    public function test_client_can_initiate_paypal_payment()
    {
        $client = User::factory()->create(['role' => 'client']);
        $job = Job::factory()->create(['client_id' => $client->id]);
        $milestone = Milestone::factory()->create(['job_id' => $job->id]);

        $response = $this->actingAs($client)->postJson("/payments/{$milestone->id}/paypal");

        $response->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonStructure(['redirect_url']);

        $this->assertDatabaseHas('payments', [
            'milestone_id' => $milestone->id,
            'payment_method' => 'paypal',
            'status' => 'pending',
        ]);
    }

    public function test_mpesa_callback_updates_payment_status()
    {
        $client = User::factory()->create(['role' => 'client']);
        $job = Job::factory()->create(['client_id' => $client->id]);
        $milestone = Milestone::factory()->create(['job_id' => $job->id]);
        $payment = Payment::factory()->create([
            'milestone_id' => $milestone->id,
            'payment_method' => 'mpesa',
            'status' => 'pending',
            'payment_details' => ['CheckoutRequestID' => 'ws_CO_123456789'],
        ]);

        $response = $this->postJson('/payments/mpesa/callback', [
            'Body' => [
                'stkCallback' => [
                    'CheckoutRequestID' => 'ws_CO_123456789',
                    'ResultCode' => 0,
                    'ResultDesc' => 'The service request is processed successfully.',
                ],
            ],
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'completed',
        ]);
        $this->assertDatabaseHas('milestones', [
            'id' => $milestone->id,
            'status' => 'paid',
        ]);
    }

    public function test_paypal_success_updates_payment_status()
    {
        $client = User::factory()->create(['role' => 'client']);
        $job = Job::factory()->create(['client_id' => $client->id]);
        $milestone = Milestone::factory()->create(['job_id' => $job->id]);
        $payment = Payment::factory()->create([
            'milestone_id' => $milestone->id,
            'payment_method' => 'paypal',
            'status' => 'pending',
        ]);

        $response = $this->get("/payments/paypal/success?payment_id={$payment->id}&token=ORDER_123456789");

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'completed',
        ]);
        $this->assertDatabaseHas('milestones', [
            'id' => $milestone->id,
            'status' => 'paid',
        ]);
    }

    public function test_paypal_cancel_updates_payment_status()
    {
        $client = User::factory()->create(['role' => 'client']);
        $job = Job::factory()->create(['client_id' => $client->id]);
        $milestone = Milestone::factory()->create(['job_id' => $job->id]);
        $payment = Payment::factory()->create([
            'milestone_id' => $milestone->id,
            'payment_method' => 'paypal',
            'status' => 'pending',
        ]);

        $response = $this->get("/payments/paypal/cancel?payment_id={$payment->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'failed',
        ]);
    }
} 