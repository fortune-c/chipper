<div id="adminRequestModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Admin Request</h3>

        <p id="adminRequestMessage" class="py-4"></p>

        <form id="approveForm" method="POST" class="flex gap-2">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">Approve</button>
        </form>
        <form id="rejectForm" method="POST" class="flex gap-2 mt-2">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-error btn-sm">Reject</button>
        </form>

        <div class="modal-action">
            <button class="btn btn-outline btn-sm" onclick="closeAdminRequestModal()">Close</button>
        </div>
    </div>
</div>

<script>
function openAdminRequestModal(button) {
    const modal = document.getElementById('adminRequestModal');
    const message = document.getElementById('adminRequestMessage');

    const userId = button.dataset.userId;
    const userName = button.dataset.userName;

    message.textContent = `${userName} has requested admin access.`;

    document.getElementById('approveForm').action = `/admin/approve/${userId}`;
    document.getElementById('rejectForm').action = `/admin/reject/${userId}`;

    modal.classList.add('modal-open');
}

function closeAdminRequestModal() {
    document.getElementById('adminRequestModal').classList.remove('modal-open');
}

</script>
