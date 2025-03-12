<?php

namespace App\Controllers;

use App\Models\Bid;
use App\Models\Job;
use App\Models\User;
use Config\Database;

class BidController extends BaseController
{
    protected $bid;
    protected $job;
    protected $user;
    protected $db;

    public function __construct()
    {
        $this->bid = new Bid();
        $this->job = new Job();
        $this->user = new User();
        $this->db = Database::connect();
    }

    public function store($jobId)
    {
        if (session()->get('user')['role'] !== 'freelancer') {
            return redirect()->to('jobs/' . $jobId)->with('error', 'Only freelancers can submit bids');
        }

        $job = $this->job->find($jobId);
        if (!$job) {
            return redirect()->to('jobs')->with('error', 'Job not found');
        }

        if ($job['status'] !== 'open') {
            return redirect()->to('jobs/' . $jobId)->with('error', 'This job is no longer accepting bids');
        }

        if ($this->bid->hasUserBidOnJob(session()->get('user')['id'], $jobId)) {
            return redirect()->to('jobs/' . $jobId)->with('error', 'You have already submitted a bid for this job');
        }

        if (!$this->validate($this->bid->validationRules, $this->bid->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id' => session()->get('user')['id'],
            'job_id' => $jobId,
            'amount' => $this->request->getPost('amount'),
            'proposal' => $this->request->getPost('proposal'),
            'delivery_time' => $this->request->getPost('delivery_time'),
            'status' => 'pending'
        ];

        $this->bid->insert($data);

        return redirect()->to('jobs/' . $jobId)->with('success', 'Bid submitted successfully');
    }

    public function show($id)
    {
        $bid = $this->bid->getBidWithDetails($id);
        if (!$bid) {
            return redirect()->to('jobs')->with('error', 'Bid not found');
        }

        // Check if user has permission to view this bid
        $userId = session()->get('user')['id'];
        $job = $this->job->find($bid['job_id']);
        
        if ($userId !== $bid['user_id'] && $userId !== $job['user_id']) {
            return redirect()->to('jobs')->with('error', 'You do not have permission to view this bid');
        }

        return view('bids/show', ['bid' => $bid]);
    }

    public function accept($id)
    {
        $bid = $this->bid->find($id);
        if (!$bid) {
            return redirect()->to('jobs')->with('error', 'Bid not found');
        }

        $job = $this->job->find($bid['job_id']);
        if (!$job) {
            return redirect()->to('jobs')->with('error', 'Job not found');
        }

        if ($job['user_id'] !== session()->get('user')['id']) {
            return redirect()->to('jobs')->with('error', 'You can only accept bids on your own jobs');
        }

        if ($job['status'] !== 'open') {
            return redirect()->to('jobs/' . $job['id'])->with('error', 'This job is no longer accepting bids');
        }

        // Start a transaction
        $this->db->transStart();

        // Update bid status
        $this->bid->update($id, ['status' => 'accepted']);

        // Update job status
        $this->job->update($bid['job_id'], ['status' => 'in_progress']);

        // Reject all other bids
        $this->bid->where('job_id', $bid['job_id'])
            ->where('id !=', $id)
            ->set(['status' => 'rejected'])
            ->update();

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->to('jobs/' . $job['id'])->with('error', 'An error occurred while accepting the bid');
        }

        return redirect()->to('jobs/' . $job['id'])->with('success', 'Bid accepted successfully');
    }

    public function reject($id)
    {
        $bid = $this->bid->find($id);
        if (!$bid) {
            return redirect()->to('jobs')->with('error', 'Bid not found');
        }

        $job = $this->job->find($bid['job_id']);
        if (!$job) {
            return redirect()->to('jobs')->with('error', 'Job not found');
        }

        if ($job['user_id'] !== session()->get('user')['id']) {
            return redirect()->to('jobs')->with('error', 'You can only reject bids on your own jobs');
        }

        $this->bid->update($id, ['status' => 'rejected']);

        return redirect()->to('jobs/' . $job['id'])->with('success', 'Bid rejected successfully');
    }

    public function withdraw($id)
    {
        $bid = $this->bid->find($id);
        if (!$bid) {
            return redirect()->to('jobs')->with('error', 'Bid not found');
        }

        if ($bid['user_id'] !== session()->get('user')['id']) {
            return redirect()->to('jobs')->with('error', 'You can only withdraw your own bids');
        }

        if ($bid['status'] !== 'pending') {
            return redirect()->to('jobs/' . $bid['job_id'])->with('error', 'You cannot withdraw this bid');
        }

        $this->bid->delete($id);

        return redirect()->to('jobs/' . $bid['job_id'])->with('success', 'Bid withdrawn successfully');
    }
} 