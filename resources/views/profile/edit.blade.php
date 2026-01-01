<x-layout>
    <x-slot:title>My Profile</x-slot:title>

    <div class="max-w-xl mx-auto card bg-base-100 shadow">
        <div class="card-body space-y-4">
            <h2 class="card-title">Edit Profile</h2>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <input name="name"
                       value="{{ old('name', $user->name) }}"
                       class="input input-bordered w-full"
                       placeholder="Name"
                       required />

                <input name="email"
                       value="{{ old('email', $user->email) }}"
                       class="input input-bordered w-full"
                       placeholder="Email"
                       required />

                       <div class="form-control">
    <label class="label">
        <span class="label-text">Role in Company</span>
    </label>

<select name="role" class="select select-bordered w-full">
    <option value="">Select your role</option>
    <option value="CEO">CEO</option>
    <option value="Team Manager">Team Manager</option>
    <option value="System Administrator">System Administrator</option>
    <option value="Developer">Developer</option>
</select>


    @if($user->admin_requested)
        <p class="text-warning text-sm mt-1">
            Admin access request pending approval
        </p>
    @endif
</div>
                <textarea name="bio"
                          class="textarea textarea-bordered w-full"
                          placeholder="Bio">{{ old('bio', $user->bio) }}</textarea>

                <input type="password"
                       name="password"
                       class="input input-bordered w-full"
                       placeholder="New password (optional)" />

                <input type="password"
                       name="password_confirmation"
                       class="input input-bordered w-full"
                       placeholder="Confirm new password" />

                <input type="file"
                       name="avatar"
                       class="file-input file-input-bordered w-full" />

                @if($user->isAdmin())
                    <div class="badge badge-primary">Administrator</div>
                @endif

                <button class="btn btn-primary w-full mt-4">
                    Update Profile
                </button>
            </form>
        </div>
    </div>
</x-layout>
