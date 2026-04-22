<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with('department', 'supervisor')
                     ->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $departments = Department::all();
        $supervisors = User::whereIn('role', ['admin', 'approver'])->get();
        return view('users.create', compact('departments', 'supervisors'));
    }

    /**
     * Store a newly created user in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,approver,user',
            'department_id' => 'required|exists:departments,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'module' => 'user',
            'description' => 'Created new user: ' . $validated['name'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('users.index')
                        ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        if (auth()->id() !== $user->id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $departments = Department::all();
        $supervisors = User::whereIn('role', ['admin', 'approver'])->get();
        return view('users.edit', compact('user', 'departments', 'supervisors'));
    }

    /**
     * Update the specified user in database
     */
    public function update(Request $request, User $user)
    {
        if (auth()->id() !== $user->id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'role' => auth()->user()->isAdmin() ? 'required|in:admin,approver,user' : 'prohibited',
            'department_id' => auth()->user()->isAdmin() ? 'required|exists:departments,id' : 'prohibited',
            'supervisor_id' => auth()->user()->isAdmin() ? 'nullable|exists:users,id' : 'prohibited',
            'phone' => 'nullable|string|max:20',
            'is_active' => auth()->user()->isAdmin() ? 'required|boolean' : 'prohibited',
        ]);

        $user->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'module' => 'user',
            'description' => 'Updated user: ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('users.index')
                        ->with('success', 'User updated successfully.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request, User $user)
    {
        if (auth()->id() !== $user->id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'current_password' => 'required_if:' . auth()->id() . ',' . $user->id,
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify current password if not admin
        if (auth()->id() === $user->id) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->with('error', 'Current password is incorrect.');
            }
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'module' => 'user',
            'description' => 'Changed password for user: ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Remove the specified user from database
     */
    public function destroy(User $user)
    {
        if ($user->bookings()->exists() || $user->approvals()->exists()) {
            return back()->with('error', 'Cannot delete user with related bookings or approvals.');
        }

        $userName = $user->name;
        $user->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'module' => 'user',
            'description' => 'Deleted user: ' . $userName,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('users.index')
                        ->with('success', 'User deleted successfully.');
    }
}
