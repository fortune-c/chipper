<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    public function vote(Request $request, Poll $poll)
    {
        $validated = $request->validate([
            'option_id' => 'required|exists:poll_options,id',
        ]);

        $optionId = $validated['option_id'];

        // Check if user has already voted for this option
        // If allow_multiple_votes is false, we should clear previous votes? 
        // For simplicity, let's assume single choice for now or handled by UI constraint.
        // Actually, db constraint is unique per option/user.
        
        // If single choice poll, remove other votes by this user for this poll?
        if (!$poll->allow_multiple_votes) {
             $poll->votes()->where('user_id', Auth::id())->delete();
        }

        // Toggle vote (if already voted for this specific option, remove it, else add it)
        // Check if exists
        $existing = PollVote::where('poll_option_id', $optionId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
             $existing->delete();
        } else {
             PollVote::create([
                 'poll_option_id' => $optionId,
                 'user_id' => Auth::id(),
             ]);
        }

        return back()->with('success', 'Vote recorded.');
    }
}
