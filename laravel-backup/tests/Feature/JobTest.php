<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Job;
use App\Models\Bid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_create_job()
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->post('/jobs', [
            'title' => 'Test Job',
            'description' => 'This is a test job description',
            'category' => 'web',
            'skills_required' => ['PHP', 'Laravel'],
            'budget_min' => 100,
            'budget_max' => 500,
            'deadline' => now()->addDays(7)->format('Y-m-d'),
            'experience_level' => 'intermediate',
            'project_length' => 'medium',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jobs', [
            'title' => 'Test Job',
            'client_id' => $client->id,
        ]);
    }

    public function test_freelancer_cannot_create_job()
    {
        $freelancer = User::factory()->create(['role' => 'freelancer']);

        $response = $this->actingAs($freelancer)->post('/jobs', [
            'title' => 'Test Job',
            'description' => 'This is a test job description',
            'category' => 'web',
            'skills_required' => ['PHP', 'Laravel'],
            'budget_min' => 100,
            'budget_max' => 500,
            'deadline' => now()->addDays(7)->format('Y-m-d'),
            'experience_level' => 'intermediate',
            'project_length' => 'medium',
        ]);

        $response->assertForbidden();
    }

    public function test_freelancer_can_bid_on_job()
    {
        $client = User::factory()->create(['role' => 'client']);
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $job = Job::factory()->create(['client_id' => $client->id]);

        $response = $this->actingAs($freelancer)->post("/jobs/{$job->id}/bids", [
            'amount' => 300,
            'proposal' => 'This is my proposal for the job',
            'delivery_time' => 14,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bids', [
            'job_id' => $job->id,
            'freelancer_id' => $freelancer->id,
        ]);
    }

    public function test_client_cannot_bid_on_job()
    {
        $client = User::factory()->create(['role' => 'client']);
        $job = Job::factory()->create(['client_id' => $client->id]);

        $response = $this->actingAs($client)->post("/jobs/{$job->id}/bids", [
            'amount' => 300,
            'proposal' => 'This is my proposal for the job',
            'delivery_time' => 14,
        ]);

        $response->assertForbidden();
    }

    public function test_client_can_accept_bid()
    {
        $client = User::factory()->create(['role' => 'client']);
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $job = Job::factory()->create(['client_id' => $client->id]);
        $bid = Bid::factory()->create([
            'job_id' => $job->id,
            'freelancer_id' => $freelancer->id,
        ]);

        $response = $this->actingAs($client)->post("/jobs/{$job->id}/bids/{$bid->id}/accept");

        $response->assertRedirect();
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'status' => 'in_progress',
            'freelancer_id' => $freelancer->id,
        ]);
        $this->assertDatabaseHas('bids', [
            'id' => $bid->id,
            'status' => 'accepted',
        ]);
    }

    public function test_freelancer_cannot_accept_bid()
    {
        $client = User::factory()->create(['role' => 'client']);
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $job = Job::factory()->create(['client_id' => $client->id]);
        $bid = Bid::factory()->create([
            'job_id' => $job->id,
            'freelancer_id' => $freelancer->id,
        ]);

        $response = $this->actingAs($freelancer)->post("/jobs/{$job->id}/bids/{$bid->id}/accept");

        $response->assertForbidden();
    }

    public function test_client_can_update_own_job()
    {
        $client = User::factory()->create(['role' => 'client']);
        $job = Job::factory()->create(['client_id' => $client->id]);

        $response = $this->actingAs($client)->put("/jobs/{$job->id}", [
            'title' => 'Updated Job Title',
            'description' => 'Updated job description',
            'category' => 'web',
            'skills_required' => ['PHP', 'Laravel'],
            'budget_min' => 100,
            'budget_max' => 500,
            'deadline' => now()->addDays(7)->format('Y-m-d'),
            'experience_level' => 'intermediate',
            'project_length' => 'medium',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'title' => 'Updated Job Title',
        ]);
    }

    public function test_client_cannot_update_others_job()
    {
        $client1 = User::factory()->create(['role' => 'client']);
        $client2 = User::factory()->create(['role' => 'client']);
        $job = Job::factory()->create(['client_id' => $client1->id]);

        $response = $this->actingAs($client2)->put("/jobs/{$job->id}", [
            'title' => 'Updated Job Title',
            'description' => 'Updated job description',
            'category' => 'web',
            'skills_required' => ['PHP', 'Laravel'],
            'budget_min' => 100,
            'budget_max' => 500,
            'deadline' => now()->addDays(7)->format('Y-m-d'),
            'experience_level' => 'intermediate',
            'project_length' => 'medium',
        ]);

        $response->assertForbidden();
    }
} 