<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Facades\Activity;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'skills']);
        
        // Apply filters
        if ($request->has('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        
        // Get paginated results
        $users = $query->latest()->paginate(20);
        
        // Get roles for filter
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }
    
    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load([
            'roles',
            'skills',
            'jobs',
            'bids',
            'payments'
        ]);
        
        // Get activity log
        $activities = DB::table('activity_log')
            ->where('causer_type', 'App\Models\User')
            ->where('causer_id', $user->id)
            ->latest()
            ->take(50)
            ->get();
            
        return view('admin.users.show', compact('user', 'activities'));
    }
    
    /**
     * Update the user's status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,banned'
        ]);
        
        $user->update([
            'status' => $request->status
        ]);
        
        // Log the action
        activity()
            ->performedOn($user)
            ->withProperties(['status' => $request->status])
            ->log('updated user status');
        
        return back()->with('success', 'User status updated successfully.');
    }
    
    /**
     * Update the user's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);
        
        // Sync the new role
        $role = Role::where('name', $request->role)->first();
        $user->roles()->sync([$role->id]);
        
        // Log the action
        activity()
            ->performedOn($user)
            ->withProperties(['role' => $request->role])
            ->log('updated user role');
        
        return back()->with('success', 'User role updated successfully.');
    }
    
    /**
     * Remove the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Log the action before deletion
        activity()
            ->performedOn($user)
            ->log('deleted user');
            
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
