<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $tasks = auth('web')->user()->tasks()->orderBy('created_at', 'desc')->get();

        return view('home', compact('tasks')); // Pass $tasks to your layout
    }

    public function store(Request $request)
    {
        $request->validate(['text' => 'required|string|max:255']);

        auth('web')->user()->tasks()->create([
            'text' => $request->text,
        ]);

        return back();
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update([
            'done' => $request->has('done'),
        ]);

        return back();
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return back();
    }
}
