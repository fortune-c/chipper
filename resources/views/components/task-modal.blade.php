@props(['tasks'])

<div id="taskModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-base-100/90 backdrop-blur-md rounded-lg w-96 max-h-[80vh] overflow-y-auto p-4 relative shadow-lg border border-base-200">
        <h2 class="text-xl font-bold mb-4">All Tasks</h2>
        <button onclick="closeTaskModal()" class="absolute top-2 right-2 btn btn-ghost btn-sm">✕</button>

        <ul class="space-y-2">
            @foreach($tasks as $task)
                <li class="flex items-center justify-between">
                    <form method="POST" action="{{ route('tasks.update', $task) }}" class="flex items-center gap-2 flex-1">
                        @csrf
                        @method('PATCH')
                        <input type="checkbox" name="done" value="1" onchange="this.form.submit()" {{ $task->done ? 'checked' : '' }} class="checkbox">
                        <span class="{{ $task->done ? 'line-through text-base-content/50' : '' }}">
                            {{ $task->text }}
                        </span>
                    </form>

                    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost btn-xs text-error">✕</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
function openTaskModal() {
    document.getElementById('taskModal').classList.remove('hidden');
    document.getElementById('taskModal').classList.add('flex');
}

function closeTaskModal() {
    document.getElementById('taskModal').classList.add('hidden');
    document.getElementById('taskModal').classList.remove('flex');
}
</script>
