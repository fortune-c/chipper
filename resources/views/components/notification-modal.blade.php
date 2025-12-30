@props(['notifications'])

<div id="notificationModal"
     class="fixed inset-0 hidden items-center justify-center z-50 bg-black/30 backdrop-blur-sm">
    <div class="bg-base-100/90 backdrop-blur-md rounded-xl w-full max-w-md max-h-[80vh] overflow-y-auto p-4 shadow-xl border">
        <h2 class="text-xl font-bold mb-4">All Notifications</h2>

        <button onclick="closeNotificationModal()"
                class="absolute top-3 right-3 btn btn-ghost btn-sm">✕</button>

        <ul class="space-y-3">
            @foreach($notifications as $notification)
                <li class="border-b pb-2">
                    <div class="font-semibold">{{ $notification->data['title'] }}</div>
                    <div class="text-sm text-base-content/60">
                        {{ \Carbon\Carbon::parse($notification->data['starts_at'])->format('M j, Y @ H:i') }}
                    </div>
                    @if(!$notification->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-primary mt-1">Mark as Read</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
function openNotificationModal() {
    const modal = document.getElementById('notificationModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeNotificationModal() {
    const modal = document.getElementById('notificationModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>