<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

class LoginController extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        // Redirect to dashboard if already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function login()
    {
        // Validate form input
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Check credentials
        $user = $this->user->where('email', $this->request->getPost('email'))->first();

        if (!$user || !password_verify($this->request->getPost('password'), $user->password)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid login credentials.');
        }

        // Check if email is verified
        if (!$user->email_verified_at) {
            return redirect()->to('/email/verify')
                ->with('warning', 'Please verify your email address before logging in.');
        }

        // Set user session
        $this->setUserSession($user);

        // Handle remember me
        if ($this->request->getPost('remember')) {
            $this->setRememberMeToken($user);
        }

        return redirect()->to('/dashboard')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    public function logout()
    {
        // Clear remember me token if exists
        if ($this->request->getCookie('remember_token')) {
            $this->user->update(session()->get('id'), ['remember_token' => null]);
            delete_cookie('remember_token');
        }

        $this->clearUserSession();

        return redirect()->to('/login')
            ->with('success', 'You have been logged out successfully.');
    }

    protected function setRememberMeToken($user)
    {
        $token = $this->generateVerificationToken();
        
        // Save token in database
        $this->user->update($user->id, ['remember_token' => $token]);
        
        // Set cookie for 30 days
        set_cookie('remember_token', $token, 30 * 24 * 60 * 60);
    }

    protected function getUserFromRememberToken()
    {
        $token = get_cookie('remember_token');
        
        if (!$token) {
            return null;
        }
        
        return $this->user->where('remember_token', $token)->first();
    }
}
