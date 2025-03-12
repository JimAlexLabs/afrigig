<?php

namespace App\Controllers;

use App\Models\Job;
use App\Models\Bid;
use App\Models\User;

class DashboardController extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        $user = new User();
        $job = new Job();
        $bid = new Bid();

        $data = [
            'user' => $user->find(session()->get('id')),
        ];

        if (session()->get('role') === 'client') {
            $data['posted_jobs'] = $job->where('user_id', session()->get('id'))->findAll();
            $data['active_jobs'] = $job->where('user_id', session()->get('id'))
                ->where('status', 'active')
                ->findAll();
            $data['completed_jobs'] = $job->where('user_id', session()->get('id'))
                ->where('status', 'completed')
                ->findAll();
            $data['recent_bids'] = $bid->getRecentBidsForClientJobs(session()->get('id'));
        } else {
            $data['submitted_bids'] = $bid->where('user_id', session()->get('id'))->findAll();
            $data['active_bids'] = $bid->where('user_id', session()->get('id'))
                ->where('status', 'accepted')
                ->findAll();
            $data['completed_jobs'] = $bid->where('user_id', session()->get('id'))
                ->where('status', 'completed')
                ->findAll();
            $data['available_jobs'] = $job->getAvailableJobs();
        }

        return view('dashboard/index', $data);
    }
} 