@props(['meetings'])

<div id="meetingModal"
     class="fixed inset-0 hidden items-center justify-center z-50
            bg-black/30 backdrop-blur-sm">

    <div class="bg-base-100/90 backdrop-blur-md rounded-xl
                w-full max-w-lg max-h-[80vh]
                overflow-y-auto p-4 shadow-xl border">

        <h2 class="text-xl font-bold mb-4">All Meetings</h2>

        <button onclick="closeMeetingModal()"
                class="absolute top-3 right-3 btn btn-ghost btn-sm">
            ✕
        </button>

        <ul class="space-y-3">
            @foreach($meetings as $meeting)
                <li class="border-b pb-2">
                    <div class="font-semibold">{{ $meeting->title }}</div>

                    <div class="text-sm text-base-content/60">
                        {{ $meeting->starts_at->format('D, M j • H:i') }}
                    </div>

                    @if($meeting->meeting_link)
                        <a href="{{ $meeting->meeting_link }}"
                           target="_blank"
                           class="link link-primary text-sm">
                            Join meeting
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
function openMeetingModal() {
    meetingModal.classList.remove('hidden');
    meetingModal.classList.add('flex');
}

function closeMeetingModal() {
    meetingModal.classList.add('hidden');
    meetingModal.classList.remove('flex');
}
</script>
