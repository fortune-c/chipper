<div id="statusModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-base-100 rounded-lg p-6 w-full max-w-sm">
        <h3 class="font-bold text-lg mb-4">Set Status</h3>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status_update_only" value="1">
            <input type="hidden" name="name" value="{{ auth()->user()->name }}"> <!-- Required by validation -->
            
            <div class="form-control mb-4">
                <label class="label">Emoji</label>
                <div class="join">
                    <input type="text" name="status_emoji" value="{{ auth()->user()->status_emoji }}" placeholder="😀" class="input input-bordered join-item w-20 text-center text-xl">
                    <select class="select select-bordered join-item flex-1" onchange="this.previousElementSibling.value = this.value">
                        <option value="">Select...</option>
                        <option value="💻">💻 Working</option>
                        <option value="🗓️">🗓️ In a meeting</option>
                        <option value="☕">☕ On break</option>
                        <option value="🤒">🤒 Sick</option>
                        <option value="🌴">🌴 Vacation</option>
                        <option value="🏠">🏠 WFH</option>
                    </select>
                </div>
            </div>
            
            <div class="form-control mb-4">
                <label class="label">Message</label>
                <input type="text" name="status_message" value="{{ auth()->user()->status_message }}" placeholder="What are you doing?" class="input input-bordered w-full" maxlength="100">
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('statusModal').classList.add('hidden')" class="btn btn-ghost">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function openStatusModal() {
    document.getElementById('statusModal').classList.remove('hidden');
}
</script>
