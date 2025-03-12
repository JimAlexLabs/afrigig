<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

class ForgotPasswordController extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        return view('auth/forgot-password');
    }

    public function sendResetLink()
    {
        // Validate form input
        $rules = [
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get user by email
        $user = new User();
        $user = $user->where('email', $this->request->getPost('email'))->first();

        // Generate password reset token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in database
        if ($user) {
            $user->update([
                'reset_token' => $token,
                'reset_token_expires_at' => $expires
            ]);

            // Send password reset email
            $email = \Config\Services::email();
            $email->setFrom('noreply@afrigig.com', 'AfriGig');
            $email->setTo($user->email);
            $email->setSubject('Reset Your Password');
            $email->setMessage(view('auth/emails/reset-password', [
                'token' => $token,
                'name' => $user->name
            ]));
            $email->send();
        }

        // Always show success message to prevent email enumeration
        return redirect()->back()->with('message', 'If an account exists with that email address, you will receive password reset instructions.');
    }
}
