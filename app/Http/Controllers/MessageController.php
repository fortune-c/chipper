<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $validated = $request->validate([
            'body' => 'required_without:file|string|max:5000',
            'file' => 'nullable|file|max:10240', // 10MB max
            'type' => 'required|in:text,voice,video,image',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('messages', 'public');
        }

        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'body' => $validated['body'] ?? null,
            'type' => $validated['type'],
            'file_path' => $filePath,
        ]);

        $conversation->touch();

        broadcast(new MessageSent($message->load('user')))->toOthers();

        if ($request->expectsJson()) {
            return response()->json($message->load('user'));
        }

        return back();
    }

    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        if ($message->file_path) {
            Storage::disk('public')->delete($message->file_path);
        }

        $message->delete();

        return back()->with('success', 'Message deleted');
    }
}
