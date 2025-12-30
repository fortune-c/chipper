{{-- {{ dd($meetings) }} --}}

<x-layout>
    <x-slot:title>
        Home Feed
    </x-slot:title>

    <div class="max-w-6xl mx-auto mt-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Feed -->
        <div class="md:col-span-2 space-y-4">
            <h1 class="text-3xl font-bold">Latest Chips</h1>

            <!-- Chip Form -->
            <div class="card bg-base-100 shadow mt-4">
                <div class="card-body">
                    <form method="POST" action="/chips">
                        @csrf
                        <div class="form-control w-full">
                            <textarea name="message" placeholder="What's on your mind?"
                                class="bg-gray-100! textarea textarea-bordered w-full resize-none @error('message') textarea-error @enderror"
                                rows="4" maxlength="255" required>{{ old('message') }}</textarea>
                            <small id="charCount" class="text-base-content/60">0 / 255</small>

                            @error('message')
                                <div class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="mt-4 flex items-center justify-end">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Chip
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Feed -->
            <div class="space-y-4 mt-4">
                @forelse ($chips as $chip)
                    <x-chip :chip="$chip" />
                @empty
                    <div class="hero py-12">
                        <div class="hero-content text-center">
                            <div>
                                <svg class="mx-auto h-12 w-12 opacity-30" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <p class="mt-4 text-base-content/60">No chips yet. Be the first to chip!</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-4 my-auto">
            <!-- Tasks Card -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">My Tasks</h2>

                    <!-- New Task Form -->
                    <form method="POST" action="{{ route('tasks.store') }}" class="flex mt-2 gap-2">
                        @csrf
                        <input type="text" name="text" placeholder="New task"
                            class="bg-gray-100! input input-bordered input-sm flex-1" required>
                        <button class="btn btn-primary btn-sm">Add</button>
                    </form>

                    <!-- Task List (show max 5) -->
                    <ul class="mt-4 space-y-2">
                        @php $displayLimit = 5; @endphp
                        @forelse ($tasks->take($displayLimit) as $task)
                            <li class="flex items-center justify-between">
                                <form method="POST" action="{{ route('tasks.update', $task) }}"
                                    class="flex items-center gap-2">
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
                        @empty
                            <li class="text-base-content/60">No tasks yet</li>
                        @endforelse
                    </ul>

                    @if($tasks->count() > $displayLimit)
                        <button onclick="openTaskModal()" class="btn btn-link btn-sm mt-2">View All
                            ({{ $tasks->count() }})</button>
                    @endif
                </div>
            </div>

            <!-- Include the modal component -->
            <x-task-modal :tasks="$tasks" />


            <!-- Meeting Schedules Card -->
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Meeting Schedules</h2>

                    <ul class="mt-2 space-y-2">
                        @foreach($meetings->take(4) as $meeting)
                            <li class="flex justify-between text-sm">
                                <span>{{ $meeting->title }}</span>
                                <span class="text-base-content/60">
                                    {{ $meeting->starts_at->format('H:i') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="flex flex-row justify-between">
                        @if($meetings->count() > 4)
                            <button onclick="openMeetingModal()" class="btn btn-link btn-sm mt-2">
                                View All
                            </button>
                        @endif
                        @auth
                            @if(auth()->user()->isAdmin())
                                <button onclick="openMeetingCreateModal()" class="btn btn-primary btn-sm mt-2">
                                    Schedule Meeting
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            <x-meeting-create-modal />
            <x-meeting-schedule-modal :meetings="$meetings" />
        </div>
    </div>
</x-layout>