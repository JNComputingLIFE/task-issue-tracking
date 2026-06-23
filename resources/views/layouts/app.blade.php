<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Tracker</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">
    <nav class="bg-white border-b border-gray-200 py-4 px-6 mb-8 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-indigo-600 tracking-wide">🔧 IssueTracker</a>
            <div class="space-x-6 font-medium text-gray-600">
                <a href="{{ route('projects.index') }}" class="hover:text-indigo-600 transition">Projects</a>
                <a href="{{ route('issues.index') }}" class="hover:text-indigo-600 transition">Issues</a>
                <a href="{{ route('tags.index') }}" class="hover:text-indigo-600 transition">Tags</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded text-green-700">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>