<?php

namespace App\Http\Controllers;

use App\Models\Chip;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'emoji' => 'required|string',
            'type' => 'required|in:chip,message',
            'id' => 'required|integer',
        ]);

        $modelClass = $validated['type'] === 'chip' ? Chip::class : Message::class;
        $model = $modelClass::findOrFail($validated['id']);

        $existingReaction = $model->reactions()
            ->where('user_id', Auth::id())
            ->where('emoji', $validated['emoji'])
            ->first();

        if ($existingReaction) {
            $existingReaction->delete();
            $action = 'removed';
        } else {
            $model->reactions()->create([
                'user_id' => Auth::id(),
                'emoji' => $validated['emoji'],
            ]);
            $action = 'added';
        }

        // Return updated counts for this reaction
        $count = $model->reactions()->where('emoji', $validated['emoji'])->count();

        return response()->json([
            'action' => $action,
            'count' => $count,
        ]);
    }
}
