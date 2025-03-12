<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\User;

class RegistrationController extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        // Redirect to dashboard if already logged in
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    public function register()
    {
        // Validate form input
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirmation' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Generate verification token
        $token = $this->generateVerificationToken();

        // Create new user
        $userData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'verification_token' => $token
        ];

        $userId = $this->user->insert($userData);
        $user = $this->user->find($userId);

        // Send verification email
        $this->sendVerificationEmail($user, $token);

        // Redirect with success message
        return redirect()->to('/login')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    public function verify($token)
    {
        $user = $this->user->where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->to('/login')
                ->with('error', 'Invalid verification token.');
        }

        // Update user as verified
        $this->user->update($user->id, [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'verification_token' => null
        ]);

        return redirect()->to('/login')
            ->with('success', 'Email verified successfully! You can now log in.');
    }

    public function resendVerification()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $user = $this->user->find(session()->get('id'));

        if ($user->email_verified_at) {
            return redirect()->to('/dashboard')
                ->with('info', 'Your email is already verified.');
        }

        // Generate new verification token
        $token = $this->generateVerificationToken();
        $this->user->update($user->id, ['verification_token' => $token]);

        // Send verification email
        $this->sendVerificationEmail($user, $token);

        return redirect()->back()
            ->with('success', 'Verification link sent! Please check your email.');
    }
} 