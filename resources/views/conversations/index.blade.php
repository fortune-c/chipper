<x-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Messages</h1>
            <button onclick="document.getElementById('newConversationModal').classList.remove('hidden')" 
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                New Conversation
            </button>
        </div>

        <div class="bg-white rounded-lg shadow">
            @forelse($conversations as $conversation)
                <a href="{{ route('conversations.show', $conversation) }}" 
                   class="block p-4 border-b hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold">
                                @if($conversation->type === 'group')
                                    {{ $conversation->name }}
                                @else
                                    {{ $conversation->participants->where('id', '!=', auth()->id())->first()->name ?? 'Unknown' }}
                                @endif
                            </h3>
                            <p class="text-xs text-gray-500">
                                @if($conversation->type === 'group')
                                    {{ $conversation->participants->pluck('name')->join(', ') }}
                                @else
                                    Private conversation
                                @endif
                            </p>
                            @if($conversation->latestMessage)
                                <p class="text-sm text-gray-600 truncate mt-1">
                                    {{ $conversation->latestMessage->user->name }}: 
                                    {{ $conversation->latestMessage->body ?? '[' . ucfirst($conversation->latestMessage->type) . ']' }}
                                </p>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500">
                            {{ $conversation->updated_at->diffForHumans() }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <p>No conversations yet. Start a new one!</p>
                    <p class="text-xs mt-2">Total conversations: {{ Auth::user()->conversations()->count() }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- New Conversation Modal -->
    <div id="newConversationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h2 class="text-xl font-bold mb-4">New Conversation</h2>
            <form action="{{ route('conversations.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Type</label>
                    <select name="type" id="conversationType" class="w-full border rounded px-3 py-2">
                        <option value="private">Private Message</option>
                        <option value="group">Group Chat</option>
                    </select>
                </div>
                <div id="groupNameField" class="mb-4 hidden">
                    <label class="block text-sm font-medium mb-2">Group Name</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Participants</label>
                    <select name="participants[]" multiple required class="w-full border rounded px-3 py-2" size="5">
                        @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple users</p>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Create
                    </button>
                    <button type="button" onclick="document.getElementById('newConversationModal').classList.add('hidden')" 
                            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('conversationType').addEventListener('change', function() {
            document.getElementById('groupNameField').classList.toggle('hidden', this.value !== 'group');
        });
    </script>
</x-layout>
