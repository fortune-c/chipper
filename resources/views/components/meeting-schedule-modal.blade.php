@props(['meetings'])

<div id="meetingModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-base-100/90 backdrop-blur-md rounded-lg w-96 max-h-[80vh] overflow-y-auto p-4 relative shadow-lg border border-base-200">
        <h2 class="text-xl font-bold mb-4">All Meetings</h2>
        <button onclick="closeMeetingModal()" class="absolute top-2 right-2 btn btn-ghost btn-sm">✕</button>

        <ul class="space-y-2">
            @foreach($meetings as $meeting)
                <li class="flex justify-between">
                    <span>{{ $meeting['title'] }}</span>
                    <span class="text-sm text-base-content/60">{{ $meeting['time'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
function openMeetingModal() {
    document.getElementById('meetingModal').classList.remove('hidden');
    document.getElementById('meetingModal').classList.add('flex');
}

function closeMeetingModal() {
    document.getElementById('meetingModal').classList.add('hidden');
    document.getElementById('meetingModal').classList.remove('flex');
}
</script>
