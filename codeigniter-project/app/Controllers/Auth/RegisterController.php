<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

class RegisterController extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        return view('auth/register');
    }

    public function register()
    {
        // Validate form input
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirmation' => 'required|matches[password]',
            'role' => 'required|in_list[client,freelancer]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Create user
        $user = new User();
        $user->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'email_verified_at' => null,
            'verification_token' => bin2hex(random_bytes(32))
        ]);

        // Send verification email
        $email = \Config\Services::email();
        $email->setFrom('noreply@afrigig.com', 'AfriGig');
        $email->setTo($user->email);
        $email->setSubject('Verify Your Email Address');
        $email->setMessage(view('auth/emails/verify', ['token' => $user->verification_token]));
        $email->send();

        // Create session
        $session = session();
        $session->set([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'isLoggedIn' => true
        ]);

        return redirect()->to('/dashboard')->with('message', 'Registration successful! Please check your email to verify your account.');
    }

    public function verify($token)
    {
        $user = new User();
        $user = $user->where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Invalid verification token.');
        }

        $user->update([
            'email_verified_at' => date('Y-m-d H:i:s'),
            'verification_token' => null
        ]);

        return redirect()->to('/login')->with('message', 'Email verified successfully! You can now log in.');
    }
}
