<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Job;
use App\Models\Bid;

class ProfileController extends BaseController
{
    protected $user;
    protected $job;
    protected $bid;

    public function __construct()
    {
        $this->user = new User();
        $this->job = new Job();
        $this->bid = new Bid();
    }

    public function index()
    {
        $userId = session()->get('user')['id'];
        $user = $this->user->find($userId);

        $data = [
            'user' => $user,
            'stats' => $this->getUserStats($userId)
        ];

        return view('profile/index', $data);
    }

    public function edit()
    {
        $userId = session()->get('user')['id'];
        $user = $this->user->find($userId);

        return view('profile/edit', ['user' => $user]);
    }

    public function update()
    {
        $userId = session()->get('user')['id'];
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|max_length[255]|is_unique[users.email,id,' . $userId . ']',
            'phone' => 'permit_empty|min_length[10]|max_length[15]',
            'bio' => 'permit_empty|max_length[1000]',
            'skills' => 'permit_empty|max_length[500]',
            'avatar' => 'permit_empty|uploaded[avatar]|is_image[avatar]|max_size[avatar,1024]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'bio' => $this->request->getPost('bio'),
            'skills' => $this->request->getPost('skills')
        ];

        // Handle avatar upload
        $avatar = $this->request->getFile('avatar');
        if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {
            $newName = $avatar->getRandomName();
            $avatar->move(WRITEPATH . 'uploads/avatars', $newName);
            $data['avatar'] = $newName;

            // Delete old avatar if exists
            $user = $this->user->find($userId);
            if ($user['avatar'] && file_exists(WRITEPATH . 'uploads/avatars/' . $user['avatar'])) {
                unlink(WRITEPATH . 'uploads/avatars/' . $user['avatar']);
            }
        }

        $this->user->update($userId, $data);
        
        // Update session data
        $user = $this->user->find($userId);
        session()->set('user', $user);

        return redirect()->to('profile')->with('success', 'Profile updated successfully');
    }

    protected function getUserStats($userId)
    {
        $user = $this->user->find($userId);
        $stats = [];

        if ($user['role'] === 'client') {
            $stats['total_jobs_posted'] = $this->job->where('user_id', $userId)->countAllResults();
            $stats['active_jobs'] = $this->job->where('user_id', $userId)->where('status', 'active')->countAllResults();
            $stats['completed_jobs'] = $this->job->where('user_id', $userId)->where('status', 'completed')->countAllResults();
            $stats['total_bids_received'] = $this->bid->where('job_id IN (SELECT id FROM jobs WHERE user_id = ?)', [$userId])->countAllResults();
        } else {
            $stats['total_bids'] = $this->bid->where('user_id', $userId)->countAllResults();
            $stats['active_jobs'] = $this->bid->where('user_id', $userId)->where('status', 'accepted')->countAllResults();
            $stats['completed_jobs'] = $this->bid->where('user_id', $userId)->where('status', 'completed')->countAllResults();
            $stats['success_rate'] = $this->calculateSuccessRate($userId);
        }

        return $stats;
    }

    protected function calculateSuccessRate($userId)
    {
        $totalBids = $this->bid->where('user_id', $userId)->countAllResults();
        if ($totalBids === 0) {
            return 0;
        }

        $successfulBids = $this->bid->where('user_id', $userId)
            ->whereIn('status', ['accepted', 'completed'])
            ->countAllResults();

        return round(($successfulBids / $totalBids) * 100);
    }
} 