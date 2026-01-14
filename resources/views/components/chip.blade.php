@props(['chip', 'level' => 0])

<div class="card bg-base-100 shadow">
    <div class="card-body">
        <div class="flex space-x-3">
            @if ($chip->user)
                <div class="avatar">
                    <div class="size-10 rounded-full">
                        <img src="https://avatars.laravel.cloud/{{ urlencode($chip->user->email) }}"
                             alt="{{ $chip->user->name }}'s avatar" class="rounded-full" />
                    </div>
                </div>
            @else
                <div class="avatar placeholder">
                    <div class="size-10 rounded-full">
                        <img src="https://avatars.laravel.cloud/f61123d5-0b27-434c-a4ae-c653c7fc9ed6?vibe=stealth"
                             alt="Anonymous User" class="rounded-full" />
                    </div>
                </div>
            @endif

            <div class="min-w-0 flex-1">
                <div class="flex justify-between w-full">
                    <div class="flex items-center gap-1">
                        <span class="text-sm font-semibold">{{ $chip->user ? $chip->user->name : 'Anonymous' }}</span>
                        <span class="text-base-content/60">·</span>
                        <span class="text-sm text-base-content/60">{{ $chip->created_at->diffForHumans() }}</span>
                        @if ($chip->updated_at->gt($chip->created_at->addSeconds(5)))
                            <span class="text-base-content/60">·</span>
                            <span class="text-sm text-base-content/60 italic">edited</span>
                        @endif
                    </div>

                    @can('update', $chip)
                        <div class="flex gap-1">
                            <a href="/chips/{{ $chip->id }}/edit" class="btn btn-ghost btn-xs">Edit</a>
                            <form method="POST" action="/chips/{{ $chip->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')"
                                        class="btn btn-ghost btn-xs text-error">Delete</button>
                            </form>
                        </div>
                    @endcan
                </div>

                <p class="mt-1">{{ $chip->message }}</p>

                <!-- Media Display -->
                @if($chip->media && count($chip->media) > 0)
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        @foreach($chip->media as $media)
                            @if(str_starts_with($media['type'], 'image/'))
                                <img src="{{ asset('storage/' . $media['path']) }}" alt="Uploaded image" class="rounded-lg w-full object-cover max-h-64 cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $media['path']) }}')">
                            @elseif(str_starts_with($media['type'], 'video/'))
                                <video controls class="rounded-lg w-full max-h-64">
                                    <source src="{{ asset('storage/' . $media['path']) }}" type="{{ $media['type'] }}">
                                </video>
                            @else
                                <a href="{{ asset('storage/' . $media['path']) }}" target="_blank" class="flex items-center gap-2 p-3 bg-base-200 rounded-lg hover:bg-base-300 transition">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-sm truncate">{{ $media['name'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif

        {{-- Reply and Show Replies buttons in a row --}}
        <div class="flex gap-2 mt-2">
            <button onclick="toggleReplyForm({{ $chip->id }})" class="btn btn-ghost btn-xs">Reply</button>

            @if($chip->replies->count())
                <button onclick="toggleReplies({{ $chip->id }})" class="btn btn-ghost btn-xs">
                    Show Replies ({{ $chip->replies->count() }})
                </button>
            @endif
        </div>

        {{-- Reply form --}}
        <div id="reply-form-{{ $chip->id }}" class="mt-2 hidden">
            <form method="POST" action="/chips/{{ $chip->id }}/reply">
                @csrf
                <textarea name="message" required class="bg-gray-100! textarea textarea-bordered w-full"></textarea>
                <button type="submit" class="btn btn-primary btn-sm mt-2">Reply</button>
            </form>
        </div>

        {{-- Nested replies --}}
        @if($chip->replies->count())
            <div id="replies-{{ $chip->id }}" class="ml-4 mt-2 hidden space-y-2">
                @foreach($chip->replies as $reply)
                    <x-chip :chip="$reply" :level="$level + 1" />
                @endforeach
            </div>
        @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleReplyForm(id) {
    const el = document.getElementById(`reply-form-${id}`);
    el.style.display = el.style.display === 'none' || el.style.display === '' ? 'block' : 'none';
}

function toggleReplies(id) {
    const el = document.getElementById(`replies-${id}`);
    if (!el) return;
    el.style.display = el.style.display === 'none' || el.style.display === '' ? 'block' : 'none';
}

function openImageModal(src) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4';
    modal.onclick = () => modal.remove();
    modal.innerHTML = `<img src="${src}" class="max-w-full max-h-full rounded-lg">`;
    document.body.appendChild(modal);
}
</script>