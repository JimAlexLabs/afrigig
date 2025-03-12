<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in to access this page.');
        }

        // Check if email verification is required
        if ($session->get('email_verified_at') === null && !in_array($request->uri->getPath(), ['email/verify', 'email/resend', 'logout'])) {
            return redirect()->to('/email/verify')->with('warning', 'Please verify your email address to continue.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after the request
    }
} 