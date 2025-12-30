<div id="meetingCreateModal"
     class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-base-100 rounded-lg w-96 p-4 shadow-lg">
        <h2 class="text-lg font-bold mb-4">Schedule Meeting</h2>

        <form method="POST" action="{{ route('meetings.store') }}">
            @csrf

            <input name="title" placeholder="Title"
                   class="input input-bordered w-full mb-2" required>

            <input type="datetime-local" name="starts_at"
                   class="input input-bordered w-full mb-2" required>

            <input name="meeting_link" placeholder="Meeting link (optional)"
                   class="input input-bordered w-full mb-2">

            <textarea name="description"
                      class="textarea textarea-bordered w-full mb-3"
                      placeholder="Description"></textarea>

            <div class="flex justify-end gap-2">
                <button type="button"
                        onclick="closeMeetingCreateModal()"
                        class="btn btn-ghost btn-sm">Cancel</button>

                <button class="btn btn-primary btn-sm" type="submit">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
function openMeetingCreateModal() {
    meetingCreateModal.classList.remove('hidden')
    meetingCreateModal.classList.add('flex')
}
function closeMeetingCreateModal() {
    meetingCreateModal.classList.add('hidden')
    meetingCreateModal.classList.remove('flex')
}
</script>