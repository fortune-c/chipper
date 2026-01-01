<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminRoleRequested;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user(),
        ]);
    }

    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'role' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Define roles that require admin approval
        $adminRoles = ['CEO', 'Team Manager', 'System Administrator'];

        if (isset($validated['role']) && in_array($validated['role'], $adminRoles)) {
            // User is requesting admin access
            $validated['admin_requested'] = true;
            $validated['is_admin'] = false; // user cannot promote themselves

            // Notify super admins (CEO) of the request
            $superAdmins = User::where('is_admin', true)
                ->where('role', 'CEO')
                ->get();

            Notification::send($superAdmins, new AdminRoleRequested($user));
        } else {
            // Normal role
            $validated['admin_requested'] = false;
            $validated['is_admin'] = false;
        }

        // Update the user
        $user->update($validated);

        return back()->with('success', 'Profile updated successfully. Admin roles require approval.');
    }
}
