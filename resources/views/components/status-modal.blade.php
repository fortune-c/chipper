<div id="statusModal" class="modal">
    <div class="modal-box relative">
        <button onclick="closeStatusModal()" class="btn btn-sm btn-circle absolute right-2 top-2">✕</button>
        <h3 class="text-lg font-bold">Update Status</h3>
        
        <form action="{{ route('profile.update') }}" method="POST" class="mt-4">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status_update_only" value="true">
            
            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text">Emoji</span>
                </label>
                <input type="text" name="status_emoji" placeholder="😊" class="input input-bordered w-full @error('status_emoji') input-error @enderror" value="{{ old('status_emoji', auth()->user()->status_emoji) }}">
                @error('status_emoji')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control w-full mt-4">
                <label class="label">
                    <span class="label-text">Message</span>
                </label>
                <input type="text" name="status_message" placeholder="What's happening?" class="input input-bordered w-full @error('status_message') input-error @enderror" value="{{ old('status_message', auth()->user()->status_message) }}">
                @error('status_message')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Save Status</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openStatusModal() {
        document.getElementById('statusModal').classList.add('modal-open');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.remove('modal-open');
    }

    @if($errors->hasAny(['status_emoji', 'status_message']))
        document.addEventListener('DOMContentLoaded', function() {
            openStatusModal();
        });
    @endif
</script>
