<!DOCTYPE html>
<html lang="en" data-theme="lofi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' - Chipper' : 'Chipper' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bubblegum+Sans&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen flex flex-col bg-base-200 font-sans">
    <div class="sticky top-0 z-50">
        <nav class="navbar bg-base-100">
            <div class="navbar-start">
                <a href="/" class="text-[22px]" style="font-family: 'Bubblegum Sans', sans-serif; font-weight: 400;">
                    Chipper
                </a>
            </div>

            <div class="navbar-end gap-2">
                @auth
                    @php
                        $user = auth()->user();
                        $unreadNotifications = $user ? $user->unreadNotifications : collect();
                        $displayLimit = 5;

                        // Separate admin requests (pending approval) from the rest
                        $adminRequests = $unreadNotifications->filter(fn($n) => $n->type === \App\Notifications\AdminRoleRequested::class);
                        $otherNotifications = $unreadNotifications->diff($adminRequests);
                    @endphp


                    {{-- Notifications Bell --}}
                    @if($unreadNotifications->count() > 0)
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-circle">
                                <div class="indicator">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <span class="badge badge-xs badge-primary indicator-item">{{ $unreadNotifications->count() }}</span>
                                </div>
                            </label>

                            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-80 mt-3 z-50">
                                {{-- Admin Requests --}}
                                @if($adminRequests->count())
                                    <li class="font-semibold px-2">Admin Requests</li>
                                    @foreach($adminRequests->take($displayLimit) as $request)
                                        <li>
                                            <button
                                                class="text-sm w-full text-left"
                                                data-user-id="{{ $request->data['user_id'] }}"
                                                data-user-name="{{ $request->data['name'] }}"
                                                onclick="openAdminRequestModal(this)">
                                                {{ $request->data['name'] }} wants admin access
                                            </button>
                                        </li>
                                    @endforeach
                                @endif

                                {{-- Other Notifications (including approval/rejection, meetings, etc) --}}
                                @if($otherNotifications->count())
                                    <li class="font-semibold px-2 mt-2">Other Notifications</li>
                                    @foreach($otherNotifications->take($displayLimit) as $notification)
                                        <li>
                                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                                @csrf
                                                <button type="submit" class="text-sm w-full text-left">
                                                    {{ $notification->data['title'] ?? $notification->data['message'] ?? 'Notification' }}
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                @endif

                                {{-- View All --}}
                                @if($unreadNotifications->count() > $displayLimit)
                                    <li class="mt-1 text-center">
                                        <button onclick="openAllNotificationsModal()" class="btn btn-link btn-sm w-full">
                                            View All ({{ $unreadNotifications->count() }})
                                        </button>
                                    </li>
                                @endif

                                {{-- Clear All --}}
                                @if($unreadNotifications->count())
                                    <li class="mt-1 text-center">
                                        <form method="POST" action="{{ route('notifications.clear') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-link btn-sm w-full">Clear All</button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    {{-- Profile Dropdown --}}
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-ghost btn-sm normal-case">
                            {{ $user->name }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </label>

                        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-44 z-50">
                            <li><a href="{{ route('profile.show') }}">View Profile</a></li>
                            <li><a href="{{ route('profile.edit') }}">Edit Profile</a></li>
                            <li class="border-t mt-1">
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-sm">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="/login" class="btn btn-ghost btn-sm">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a>
                @endauth
            </div>
        </nav>
    </div>

    <!-- Success Toast -->
    @if (session('success'))
        <div class="toast toast-top toast-center">
            <div class="alert alert-success animate-fade-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <main class="flex-1 container mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    @auth
        <x-notification-modal :notifications="$unreadNotifications" />
        <x-admin-request-modal />
    @endauth
</body>
</html>
