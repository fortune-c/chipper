<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    /**
     * Approve a user's admin request.
     */
    public function approve(User $user)
    {
        $authUser = Auth::user();

        // Only super admins or CEOs can approve
        if (! $authUser->super_admin) {
            abort(403, 'Unauthorized');
        }

        $user->update([
            'is_admin' => true,
            'admin_requested' => false,
        ]);

        // Mark admin request notifications as read for the approver
        $authUser->unreadNotifications
            ->where('type', \App\Notifications\AdminRoleRequested::class)
            ->where('data.user_id', $user->id)
            ->each->markAsRead();

        // notify the user
        $user->notify(new \App\Notifications\AdminApproved());

        return back()->with('success', "{$user->name} has been approved as admin.");
    }

    /**
     * Reject a user's admin request.
     */
    public function reject(User $user)
    {
        $authUser = Auth::user();

        if (! $authUser->super_admin) {
            abort(403, 'Unauthorized');
        }

        $user->update([
            'admin_requested' => false,
        ]);

        // Mark admin request notifications as read for the approver
        $authUser->unreadNotifications
            ->where('type', \App\Notifications\AdminRoleRequested::class)
            ->where('data.user_id', $user->id)
            ->each->markAsRead();

        // notify the user
        $user->notify(new \App\Notifications\AdminRejected());

        return back()->with('success', "{$user->name}'s admin request has been rejected.");
    }
}
