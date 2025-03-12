<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SettingsController extends Controller
{
    public function notifications()
    {
        $user = Auth::user();
        return view('settings.notifications', compact('user'));
    }

    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'bid_updates' => 'boolean',
            'job_alerts' => 'boolean',
            'message_notifications' => 'boolean',
        ]);

        $user->update($validated);

        return back()->with('success', 'Notification settings updated successfully.');
    }

    public function security()
    {
        $user = Auth::user();
        return view('settings.security', compact('user'));
    }

    public function updateSecurity(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
            'two_factor_enabled' => 'boolean',
        ]);

        $user->update([
            'password' => bcrypt($validated['password']),
            'two_factor_enabled' => $validated['two_factor_enabled'] ?? false,
        ]);

        return back()->with('success', 'Security settings updated successfully.');
    }
} 