<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Notifications\MeetingCreated;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $this->authorize('create', Meeting::class);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'starts_at' => 'required|date',
            'meeting_link' => 'nullable|url',
            'description' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        $meeting = Meeting::create($data);

        // Notify all other users
        User::all()->each(fn ($user) => $user->notify(new MeetingCreated($meeting)));

        return back()->with('success', 'Meeting scheduled');
    }
}
