<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with(['participants', 'latestMessage.user'])
            ->latest('updated_at')
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $messages = $conversation->messages()
            ->with('user')
            ->latest()
            ->paginate(50);

        return view('conversations.show', compact('conversation', 'messages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:private,group',
            'name' => 'required_if:type,group|string|max:255',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
        ]);

        $conversation = Conversation::create([
            'type' => $validated['type'],
            'name' => $validated['name'] ?? null,
            'created_by' => Auth::id(),
        ]);

        $participants = array_unique(array_merge($validated['participants'], [Auth::id()]));
        $conversation->participants()->attach($participants);

        return redirect()->route('conversations.show', $conversation);
    }

    public function createPrivate(User $user)
    {
        // Check if conversation already exists
        $existing = Auth::user()->conversations()
            ->where('type', 'private')
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereDoesntHave('participants', function ($query) use ($user) {
                $query->where('user_id', '!=', $user->id)
                    ->where('user_id', '!=', Auth::id());
            })
            ->first();

        if ($existing) {
            return redirect()->route('conversations.show', $existing);
        }

        $conversation = Conversation::create([
            'type' => 'private',
            'created_by' => Auth::id(),
        ]);

        $conversation->participants()->attach([Auth::id(), $user->id]);

        return redirect()->route('conversations.show', $conversation);
    }

    public function addParticipants(Request $request, Conversation $conversation)
    {
        $this->authorize('update', $conversation);

        $validated = $request->validate([
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
        ]);

        $conversation->participants()->syncWithoutDetaching($validated['participants']);

        return back()->with('success', 'Participants added successfully');
    }
}
