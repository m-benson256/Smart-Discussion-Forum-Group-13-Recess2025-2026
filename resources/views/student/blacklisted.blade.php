<!DOCTYPE html>
<html>
<head>
    <title>Access Restricted</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md text-center">
        <h1 class="text-2xl font-bold text-red-600 mb-4">You've been blacklisted</h1>
        <p class="text-gray-700 mb-4">
            You currently cannot access your dashboard due to inactivity or a policy violation.
        </p>
        <p class="text-gray-900 font-semibold">
            You can regain access on: {{ $until->format('F j, Y \a\t g:i A') }}
        </p>
    </div>
</body>
</html>