<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ConversationController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with(['participants', 'latestMessage.user'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    public function show(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        // Handle AJAX request for new messages
        if ($request->ajax() || $request->has('ajax')) {
            $afterId = $request->get('after', 0);
            $newMessages = $conversation->messages()
                ->where('id', '>', $afterId)
                ->with('user')
                ->orderBy('id', 'asc')
                ->get()
                ->map(function($msg) {
                    return [
                        'id' => $msg->id,
                        'body' => $msg->body,
                        'type' => $msg->type,
                        'file_path' => $msg->file_path,
                        'user_name' => $msg->user->name,
                        'is_own' => $msg->user_id === auth()->id(),
                        'time' => $msg->created_at->format('g:i A'),
                    ];
                });

            return response()->json(['messages' => $newMessages]);
        }

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
            'name' => 'nullable|required_if:type,group|string|max:255',
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

        return redirect()->route('conversations.index')->with('success', 'Conversation created successfully');
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
