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
                    <form method="POST" action="{{ route('chips.store') }}" enctype="multipart/form-data" id="chipForm">
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

                        <!-- Media Upload Area -->
                        <div class="mt-4">
                            <div id="dropZone" class="border-2 border-dashed border-base-300 rounded-lg p-4 text-center hover:border-primary transition-colors cursor-pointer">
                                <input type="file" name="media[]" id="mediaInput" multiple accept="image/*,video/*,.gif,.pdf,.doc,.docx,.xls,.xlsx" class="hidden">
                                <svg class="mx-auto h-8 w-8 text-base-content/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mt-2 text-sm text-base-content/70">Drop files here or click to upload</p>
                                <p class="text-xs text-base-content/50 mt-1">Images, videos, GIFs, PDFs, documents (max 10MB each)</p>
                            </div>
                            <div id="previewArea" class="mt-3 grid grid-cols-4 gap-2"></div>
                        </div>

                        <!-- Poll Creation (Hidden by default) -->
                        <div id="pollFields" class="hidden mt-4 border-t pt-4">
                            <input type="text" name="poll_question" placeholder="Ask a question..." class="input input-bordered w-full mb-2">
                            <div id="pollOptions" class="space-y-2">
                                <input type="text" name="poll_options[]" placeholder="Option 1" class="input input-bordered input-sm w-full">
                                <input type="text" name="poll_options[]" placeholder="Option 2" class="input input-bordered input-sm w-full">
                            </div>
                            <button type="button" onclick="addPollOption()" class="btn btn-ghost btn-xs mt-2">+ Add Option</button>
                            <label class="cursor-pointer label justify-start gap-2">
                                <input type="checkbox" name="poll_multiple" class="checkbox checkbox-xs">
                                <span class="label-text-alt">Allow multiple votes</span>
                            </label>
                        </div>
                        
                        <div class="mt-2 flex gap-2">
                             <button type="button" onclick="document.getElementById('pollFields').classList.toggle('hidden')" class="btn btn-ghost btn-sm" title="Create Poll">
                                📊 Poll
                             </button>
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
        <div class="space-y-4">
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

    <script>
        const dropZone = document.getElementById('dropZone');
        const mediaInput = document.getElementById('mediaInput');
        const previewArea = document.getElementById('previewArea');
        const chipForm = document.getElementById('chipForm');
        let selectedFiles = [];

        // Prevent form from clearing files on submit
        chipForm.addEventListener('submit', (e) => {
            if (selectedFiles.length > 0) {
                e.preventDefault();
                const formData = new FormData(chipForm);
                
                // Remove any existing media files from FormData
                formData.delete('media[]');
                
                // Ensure no _method override exists (we want pure POST)
                formData.delete('_method');
                
                // Add our selected files
                selectedFiles.forEach(file => {
                    formData.append('media[]', file);
                });
                
                // Submit via fetch
                fetch(chipForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else if (response.ok) {
                        window.location.reload();
                    } else {
                        return response.json().then(data => {
                            alert('Error: ' + (data.message || 'Upload failed'));
                        });
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('Upload failed. Check console for details.');
                });
            }
        });

        dropZone.addEventListener('click', () => mediaInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-primary', 'bg-primary/5');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-primary', 'bg-primary/5');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-primary', 'bg-primary/5');
            handleFiles(e.dataTransfer.files);
        });

        mediaInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFiles(e.target.files);
            }
        });

        function handleFiles(files) {
            for (let file of files) {
                if (file.size > 10 * 1024 * 1024) {
                    alert(`${file.name} is too large. Max size is 10MB.`);
                    continue;
                }
                selectedFiles.push(file);
            }
            
            updatePreview();
        }

        function updatePreview() {
            previewArea.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const preview = document.createElement('div');
                preview.className = 'relative group';
                
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'w-full h-20 object-cover rounded';
                    preview.appendChild(img);
                } else if (file.type.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.src = URL.createObjectURL(file);
                    video.className = 'w-full h-20 object-cover rounded';
                    preview.appendChild(video);
                } else {
                    const icon = document.createElement('div');
                    icon.className = 'w-full h-20 bg-base-200 rounded flex items-center justify-center';
                    icon.innerHTML = `<svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>`;
                    preview.appendChild(icon);
                }
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute top-1 right-1 btn btn-circle btn-xs btn-error opacity-0 group-hover:opacity-100 transition-opacity';
                removeBtn.innerHTML = '×';
                removeBtn.onclick = () => removeFile(index);
                preview.appendChild(removeBtn);
                
                previewArea.appendChild(preview);
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updatePreview();
        }

        function addPollOption() {
            const container = document.getElementById('pollOptions');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'poll_options[]';
            input.placeholder = `Option ${container.children.length + 1}`;
            input.className = 'input input-bordered input-sm w-full';
            container.appendChild(input);
        }

        // Auto-refresh chips every 5 seconds
        let lastChipId = {{ $chips->first()->id ?? 0 }};
        
        setInterval(() => {
            fetch(`/?ajax=1&after=${lastChipId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.chips && data.chips.length > 0) {
                        const container = document.querySelector('.space-y-6');
                        data.chips.forEach(chip => {
                            const chipDiv = document.createElement('div');
                            chipDiv.innerHTML = chip.html;
                            container.insertBefore(chipDiv.firstElementChild, container.firstChild);
                            lastChipId = chip.id;
                        });
                    }
                })
                .catch(err => console.error('Error fetching chips:', err));
        }, 5000);

        // Auto-refresh tasks every 5 seconds
        @auth
        setInterval(() => {
            fetch('/tasks?ajax=1')
                .then(response => response.json())
                .then(data => {
                    if (data.tasks) {
                        const taskList = document.querySelector('.space-y-2');
                        if (taskList) {
                            taskList.innerHTML = data.html;
                        }
                    }
                })
                .catch(err => console.error('Error fetching tasks:', err));
        }, 5000);
        @endauth
    </script>
</x-layout>