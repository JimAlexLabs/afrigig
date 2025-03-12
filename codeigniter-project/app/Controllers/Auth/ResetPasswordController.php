<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

class ResetPasswordController extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function index($token)
    {
        $user = new User();
        $user = $user->where('reset_token', $token)
                    ->where('reset_token_expires_at >', date('Y-m-d H:i:s'))
                    ->first();

        if (!$user) {
            return redirect()->to('/forgot-password')->with('error', 'This password reset link is invalid or has expired.');
        }

        return view('auth/reset-password', ['token' => $token]);
    }

    public function reset()
    {
        // Validate form input
        $rules = [
            'token' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
            'password_confirmation' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get user by email and token
        $user = new User();
        $user = $user->where('email', $this->request->getPost('email'))
                    ->where('reset_token', $this->request->getPost('token'))
                    ->where('reset_token_expires_at >', date('Y-m-d H:i:s'))
                    ->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'This password reset link is invalid or has expired.');
        }

        // Update password
        $user->update([
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_token_expires_at' => null
        ]);

        // Send password changed notification
        $email = \Config\Services::email();
        $email->setFrom('noreply@afrigig.com', 'AfriGig');
        $email->setTo($user->email);
        $email->setSubject('Your Password Has Been Changed');
        $email->setMessage(view('auth/emails/password-changed', ['name' => $user->name]));
        $email->send();

        return redirect()->to('/login')->with('message', 'Your password has been reset! You can now log in with your new password.');
    }
}
