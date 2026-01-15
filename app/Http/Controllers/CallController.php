<?php

namespace App\Http\Controllers;

use App\Events\CallInitiated;
use App\Models\Call;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CallController extends Controller
{
    use AuthorizesRequests;
    public function initiate(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $validated = $request->validate([
            'type' => 'required|in:audio,video',
        ]);

        $call = $conversation->calls()->create([
            'initiated_by' => Auth::id(),
            'type' => $validated['type'],
            'status' => 'pending',
        ]);

        broadcast(new CallInitiated($call->load('initiator')))->toOthers();

        return response()->json($call);
    }

    public function answer(Call $call)
    {
        $this->authorize('view', $call->conversation);

        $call->update([
            'status' => 'active',
            'started_at' => now(),
        ]);

        return response()->json($call);
    }

    public function end(Call $call)
    {
        $this->authorize('view', $call->conversation);

        $call->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        return response()->json($call);
    }

    public function toggleScreenShare(Request $request, Call $call)
    {
        $this->authorize('view', $call->conversation);

        $validated = $request->validate([
            'screen_sharing' => 'required|boolean',
        ]);

        $call->update(['screen_sharing' => $validated['screen_sharing']]);

        return response()->json($call);
    }
}
