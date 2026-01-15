<x-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow h-[calc(100vh-12rem)] flex flex-col">
            <!-- Header -->
            <div class="p-4 border-b flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-lg">
                        @if($conversation->type === 'group')
                            {{ $conversation->name }}
                        @else
                            {{ $conversation->participants->where('id', '!=', auth()->id())->first()->name ?? 'Unknown' }}
                        @endif
                    </h2>
                    <p class="text-sm text-gray-600">
                        {{ $conversation->participants->count() }} participants
                        @if($conversation->type === 'group')
                            <button onclick="document.getElementById('addParticipantsModal').classList.remove('hidden')" 
                                    class="text-blue-500 hover:underline ml-2">
                                + Add
                            </button>
                        @endif
                    </p>
                </div>
                <div class="flex gap-2">
                    <button onclick="initiateCall('audio')" class="p-2 hover:bg-gray-100 rounded" title="Audio Call">
                        🎤
                    </button>
                    <button onclick="initiateCall('video')" class="p-2 hover:bg-gray-100 rounded" title="Video Call">
                        📹
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div id="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4">
                @foreach($messages->reverse() as $message)
                    <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            @if($message->user_id !== auth()->id())
                                <p class="text-xs text-gray-600 mb-1">{{ $message->user->name }}</p>
                            @endif
                            <div class="rounded-lg p-3 {{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                                @if($message->type === 'text')
                                    <p>{{ $message->body }}</p>
                                @elseif($message->type === 'voice')
                                    <audio controls src="{{ Storage::url($message->file_path) }}" class="max-w-full"></audio>
                                @elseif($message->type === 'video')
                                    <video controls src="{{ Storage::url($message->file_path) }}" class="max-w-full"></video>
                                @elseif($message->type === 'image')
                                    <img src="{{ Storage::url($message->file_path) }}" class="max-w-full rounded">
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('g:i A') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t">
                <form action="{{ route('messages.store', $conversation) }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="type" id="messageType" value="text">
                    <input type="file" name="file" id="fileInput" class="hidden" accept="audio/*,video/*,image/*">
                    <button type="button" onclick="document.getElementById('fileInput').click()" class="p-2 hover:bg-gray-100 rounded">
                        📎
                    </button>
                    <input type="text" name="body" placeholder="Type a message..." 
                           class="flex-1 border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function initiateCall(type) {
            fetch('{{ route("calls.initiate", $conversation) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type })
            })
            .then(response => response.json())
            .then(data => {
                alert(`${type.charAt(0).toUpperCase() + type.slice(1)} call initiated!`);
                // Implement WebRTC logic here
            });
        }

        document.getElementById('fileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.type.startsWith('audio/')) {
                    document.getElementById('messageType').value = 'voice';
                } else if (file.type.startsWith('video/')) {
                    document.getElementById('messageType').value = 'video';
                } else if (file.type.startsWith('image/')) {
                    document.getElementById('messageType').value = 'image';
                }
            }
        });

        // Auto-scroll to bottom
        const container = document.getElementById('messagesContainer');
        container.scrollTop = container.scrollHeight;

        // Auto-refresh messages every 3 seconds
        let lastMessageId = {{ $messages->first()->id ?? 0 }};
        
        setInterval(() => {
            fetch(`{{ route('conversations.show', $conversation) }}?ajax=1&after=${lastMessageId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(msg => {
                            const messageDiv = document.createElement('div');
                            messageDiv.className = `flex ${msg.is_own ? 'justify-end' : 'justify-start'}`;
                            messageDiv.innerHTML = `
                                <div class="max-w-xs lg:max-w-md">
                                    ${!msg.is_own ? `<p class="text-xs text-gray-600 mb-1">${msg.user_name}</p>` : ''}
                                    <div class="rounded-lg p-3 ${msg.is_own ? 'bg-blue-500 text-white' : 'bg-gray-200'}">
                                        ${msg.body ? `<p>${msg.body}</p>` : ''}
                                        ${msg.file_path && msg.type === 'image' ? `<img src="/storage/${msg.file_path}" class="max-w-full rounded">` : ''}
                                        ${msg.file_path && msg.type === 'voice' ? `<audio controls src="/storage/${msg.file_path}" class="max-w-full"></audio>` : ''}
                                        ${msg.file_path && msg.type === 'video' ? `<video controls src="/storage/${msg.file_path}" class="max-w-full"></video>` : ''}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">${msg.time}</p>
                                </div>
                            `;
                            container.appendChild(messageDiv);
                            lastMessageId = msg.id;
                        });
                        container.scrollTop = container.scrollHeight;
                    }
                })
                .catch(err => console.error('Error fetching messages:', err));
        }, 3000);
    </script>

    <!-- Add Participants Modal -->
    @if($conversation->type === 'group')
    <div id="addParticipantsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h2 class="text-xl font-bold mb-4">Add Participants</h2>
            <form action="{{ route('conversations.participants', $conversation) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Select Users</label>
                    <select name="participants[]" multiple class="w-full border rounded px-3 py-2" size="8">
                        @foreach(\App\Models\User::whereNotIn('id', $conversation->participants->pluck('id'))->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple</p>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Add
                    </button>
                    <button type="button" onclick="document.getElementById('addParticipantsModal').classList.add('hidden')" 
                            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</x-layout>
