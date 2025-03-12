<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\User;

class AuthController extends BaseController
{
    protected $helpers = ['form', 'url'];
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    protected function setUserSession($user)
    {
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'isLoggedIn' => true,
        ];
        
        session()->set($data);
    }

    protected function clearUserSession()
    {
        session()->destroy();
    }

    protected function generateVerificationToken()
    {
        return bin2hex(random_bytes(32));
    }

    protected function sendVerificationEmail($user, $token)
    {
        $email = \Config\Services::email();
        $email->setFrom('noreply@afrigig.com', 'AfriGig');
        $email->setTo($user->email);
        $email->setSubject('Verify Your Email Address');
        
        $data = [
            'name' => $user->name,
            'verification_url' => site_url('verify-email/' . $token)
        ];
        
        $email->setMessage(view('auth/emails/verify-email', $data));
        return $email->send();
    }

    protected function sendPasswordResetEmail($user, $token)
    {
        $email = \Config\Services::email();
        $email->setFrom('noreply@afrigig.com', 'AfriGig');
        $email->setTo($user->email);
        $email->setSubject('Reset Your Password');
        
        $data = [
            'name' => $user->name,
            'reset_url' => site_url('reset-password/' . $token)
        ];
        
        $email->setMessage(view('auth/emails/reset-password', $data));
        return $email->send();
    }

    protected function sendPasswordChangedEmail($user)
    {
        $email = \Config\Services::email();
        $email->setFrom('noreply@afrigig.com', 'AfriGig');
        $email->setTo($user->email);
        $email->setSubject('Password Changed Successfully');
        
        $data = [
            'name' => $user->name
        ];
        
        $email->setMessage(view('auth/emails/password-changed', $data));
        return $email->send();
    }
} 