<!DOCTYPE html>
<html>
<head>
    <title>Access Restricted</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md text-center">
        @if($reason === 'blocked')
            <h1 class="text-2xl font-bold text-red-600 mb-4">You've been blocked</h1>
            <p class="text-gray-700 mb-6">
                Your account has been blocked by an administrator. Please contact support for assistance.
            </p>
        @else
            <h1 class="text-2xl font-bold text-red-600 mb-4">You've been blacklisted</h1>
            <p class="text-gray-700 mb-4">
                You currently cannot access your dashboard due to inactivity.
            </p>
            <p class="text-gray-900 font-semibold mb-6">
                You can regain access on: {{ $until->format('F j, Y \a\t g:i A') }}
            </p>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Log Out
            </button>
        </form>
    </div>
</body>
</html>