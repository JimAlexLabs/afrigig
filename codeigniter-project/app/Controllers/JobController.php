<?php

namespace App\Controllers;

use App\Models\Job;
use App\Models\Bid;
use App\Models\User;

class JobController extends BaseController
{
    protected $job;
    protected $bid;
    protected $user;

    public function __construct()
    {
        $this->job = new Job();
        $this->bid = new Bid();
        $this->user = new User();
    }

    public function index()
    {
        $keyword = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $minBudget = $this->request->getGet('min_budget');
        $maxBudget = $this->request->getGet('max_budget');

        if ($keyword || $category || $minBudget || $maxBudget) {
            $jobs = $this->job->searchJobs($keyword, $category, $minBudget, $maxBudget);
        } else {
            $jobs = $this->job->getAvailableJobs();
        }

        $data = [
            'jobs' => $jobs,
            'categories' => [
                'Web Development',
                'Mobile Development',
                'UI/UX Design',
                'Content Writing',
                'Digital Marketing',
                'Data Science',
                'Other'
            ]
        ];

        return view('jobs/index', $data);
    }

    public function create()
    {
        if (session()->get('user')['role'] !== 'client') {
            return redirect()->to('jobs')->with('error', 'Only clients can post jobs');
        }

        $data = [
            'categories' => [
                'Web Development',
                'Mobile Development',
                'UI/UX Design',
                'Content Writing',
                'Digital Marketing',
                'Data Science',
                'Other'
            ]
        ];

        return view('jobs/create', $data);
    }

    public function store()
    {
        if (session()->get('user')['role'] !== 'client') {
            return redirect()->to('jobs')->with('error', 'Only clients can post jobs');
        }

        if (!$this->validate($this->job->validationRules, $this->job->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id' => session()->get('user')['id'],
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'requirements' => $this->request->getPost('requirements'),
            'budget' => $this->request->getPost('budget'),
            'duration' => $this->request->getPost('duration'),
            'category' => $this->request->getPost('category'),
            'deadline' => $this->request->getPost('deadline'),
            'status' => 'open'
        ];

        $this->job->insert($data);

        return redirect()->to('jobs')->with('success', 'Job posted successfully');
    }

    public function show($id)
    {
        $job = $this->job->getJobWithDetails($id);
        if (!$job) {
            return redirect()->to('jobs')->with('error', 'Job not found');
        }

        $data = [
            'job' => $job,
            'bids' => $this->bid->getBidsForJob($id),
            'has_bid' => false
        ];

        if (session()->get('user')['role'] === 'freelancer') {
            $data['has_bid'] = $this->bid->hasUserBidOnJob(session()->get('user')['id'], $id);
        }

        return view('jobs/show', $data);
    }

    public function edit($id)
    {
        $job = $this->job->find($id);
        if (!$job) {
            return redirect()->to('jobs')->with('error', 'Job not found');
        }

        if ($job['user_id'] !== session()->get('user')['id']) {
            return redirect()->to('jobs')->with('error', 'You can only edit your own jobs');
        }

        $data = [
            'job' => $job,
            'categories' => [
                'Web Development',
                'Mobile Development',
                'UI/UX Design',
                'Content Writing',
                'Digital Marketing',
                'Data Science',
                'Other'
            ]
        ];

        return view('jobs/edit', $data);
    }

    public function update($id)
    {
        $job = $this->job->find($id);
        if (!$job) {
            return redirect()->to('jobs')->with('error', 'Job not found');
        }

        if ($job['user_id'] !== session()->get('user')['id']) {
            return redirect()->to('jobs')->with('error', 'You can only edit your own jobs');
        }

        if (!$this->validate($this->job->validationRules, $this->job->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'requirements' => $this->request->getPost('requirements'),
            'budget' => $this->request->getPost('budget'),
            'duration' => $this->request->getPost('duration'),
            'category' => $this->request->getPost('category'),
            'deadline' => $this->request->getPost('deadline')
        ];

        $this->job->update($id, $data);

        return redirect()->to('jobs/' . $id)->with('success', 'Job updated successfully');
    }

    public function close($id)
    {
        $job = $this->job->find($id);
        if (!$job) {
            return redirect()->to('jobs')->with('error', 'Job not found');
        }

        if ($job['user_id'] !== session()->get('user')['id']) {
            return redirect()->to('jobs')->with('error', 'You can only close your own jobs');
        }

        $this->job->update($id, ['status' => 'closed']);

        return redirect()->to('jobs/' . $id)->with('success', 'Job closed successfully');
    }

    public function myJobs()
    {
        if (session()->get('user')['role'] === 'client') {
            $jobs = $this->job->where('user_id', session()->get('user')['id'])
                ->orderBy('created_at', 'DESC')
                ->findAll();
        } else {
            $bids = $this->bid->getFreelancerBids(session()->get('user')['id']);
            return view('jobs/my-bids', ['bids' => $bids]);
        }

        return view('jobs/my-jobs', ['jobs' => $jobs]);
    }
} 