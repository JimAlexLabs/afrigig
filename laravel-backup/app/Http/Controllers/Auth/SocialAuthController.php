<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirect based on provider
     */
    public function redirect($provider)
    {
        // Temporarily disable social login
        return redirect()->route('login')
            ->with('error', 'Social login is temporarily unavailable. Please use email registration.');
    }

    /**
     * Handle OAuth callback
     */
    public function callback($provider)
    {
        // Temporarily disable social login
        return redirect()->route('login')
            ->with('error', 'Social login is temporarily unavailable. Please use email registration.');
    }

    /**
     * Redirect to Google OAuth
     */
    public function googleRedirect()
    {
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
            return redirect()->route('login')
                ->with('error', 'Google login is not configured. Please try another method.');
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function googleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            $finduser = User::where('google_id', $user->id)->first();
            
            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'freelancer', // Default role
                    'terms_accepted' => true, // They accept terms by using social login
                ]);
                
                Auth::login($newUser);
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            return redirect('login')->with('error', 'Something went wrong with Google login');
        }
    }

    /**
     * Redirect to LinkedIn OAuth
     */
    public function linkedinRedirect()
    {
        if (!config('services.linkedin.client_id') || !config('services.linkedin.client_secret')) {
            return redirect()->route('login')
                ->with('error', 'LinkedIn login is not configured. Please try another method.');
        }

        return Socialite::driver('linkedin')->redirect();
    }

    /**
     * Handle LinkedIn callback
     */
    public function linkedinCallback()
    {
        try {
            $user = Socialite::driver('linkedin')->user();
            
            $finduser = User::where('linkedin_id', $user->id)->first();
            
            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'linkedin_id' => $user->id,
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'freelancer', // Default role
                    'terms_accepted' => true, // They accept terms by using social login
                ]);
                
                Auth::login($newUser);
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            return redirect('login')->with('error', 'Something went wrong with LinkedIn login');
        }
    }
} 