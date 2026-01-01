<x-layout>
    <x-slot:title>My Profile</x-slot:title>

    <div class="max-w-xl mx-auto">
        <div class="card bg-base-100 shadow">
            <div class="card-body items-center text-center">
                <div class="avatar mb-3">
                    <div class="w-24 rounded-full">
                        <img src="{{ $user->avatar
    ? asset('storage/' . $user->avatar)
    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}">
                    </div>
                </div>

                <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                <p class="text-base-content/60">{{ $user->bio ?? 'No bio yet' }}</p>

                @if($user->isAdmin())
                    <span class="badge badge-primary mt-2">Admin</span>
                @endif

                {{-- Show if the user has requested admin access --}}
                @if($user->admin_requested && !$user->isAdmin())
                    <span class="badge badge-warning mt-2">Admin Request Pending</span>
                @endif

                <a href="{{ route('profile.edit') }}" class="btn btn-outline btn-sm mt-4">
                    Edit Profile
                </a>

            </div>
        </div>
    </div>


</x-layout>