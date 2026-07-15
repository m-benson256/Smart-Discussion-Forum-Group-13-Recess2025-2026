<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} - Smart Discussion Forum</title>

    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/forum-share-banner.png') }}">
    <meta property="og:site_name" content="Smart Discussion Forum">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ asset('images/forum-share-banner.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-8">
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 max-w-lg w-full p-8 text-center">
        <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">
            <i class="fa-regular fa-comments"></i>
        </div>
        <h1 class="text-xl font-bold text-slate-800 mb-2">{{ $title }}</h1>
        <p class="text-slate-500 mb-6">{{ $description }}</p>
        <a href="/student/dashboard?topic={{ $topic->id }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">
            Sign in to view this discussion
        </a>
    </div>
</body>
</html>