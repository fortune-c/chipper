<x-app-layout>
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
    </script>
</x-app-layout>
