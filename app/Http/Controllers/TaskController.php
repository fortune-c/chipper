<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $tasks = auth('web')->user()->tasks()->orderBy('created_at', 'desc')->get();

        // Handle AJAX request
        if ($request->ajax() || $request->has('ajax')) {
            $displayLimit = 5;
            $html = '';
            
            if ($tasks->isEmpty()) {
                $html = '<li class="text-base-content/60">No tasks yet</li>';
            } else {
                foreach ($tasks->take($displayLimit) as $task) {
                    $checked = $task->done ? 'checked' : '';
                    $lineThrough = $task->done ? 'line-through text-base-content/50' : '';
                    $html .= '<li class="flex items-center justify-between">
                        <form method="POST" action="' . route('tasks.update', $task) . '" class="flex items-center gap-2">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="checkbox" name="done" value="1" onchange="this.form.submit()" ' . $checked . ' class="checkbox">
                            <span class="' . $lineThrough . '">' . e($task->text) . '</span>
                        </form>
                        <form method="POST" action="' . route('tasks.destroy', $task) . '">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-ghost btn-xs text-error">✕</button>
                        </form>
                    </li>';
                }
            }
            
            return response()->json(['tasks' => $tasks, 'html' => $html]);
        }

        return view('home', compact('tasks'));
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
