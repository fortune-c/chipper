
@props(['chip'])

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

                    <!-- Replace the temporary @php block and $canEdit check with: -->
                    @can('update', $chip)
                        <div class="flex gap-1">
                            <a href="/chips/{{ $chip->id }}/edit" class="btn btn-ghost btn-xs">
                                Edit
                            </a>
                            <form method="POST" action="/chips/{{ $chip->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this chip?')"
                                    class="btn btn-ghost btn-xs text-error">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
                <p class="mt-1">{{ $chip->message }}</p>
            </div>
        </div>
    </div>
</div>