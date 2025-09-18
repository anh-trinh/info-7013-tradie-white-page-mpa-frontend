<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'White Pages for Tradies')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="font-bold text-2xl text-gray-800 flex items-center">
                <span class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-md mr-2 font-mono">W</span>
                White Pages
            </a>
            <div>
                <a href="/login" class="text-gray-600 font-medium hover:text-blue-600 mr-6">Login</a>
                <a href="/register" class="bg-blue-600 text-white px-5 py-2 rounded-md font-semibold hover:bg-blue-700 transition duration-300">Register</a>
            </div>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
    <footer class="bg-white border-t mt-16">
        <div class="container mx-auto py-8 px-6 text-center text-gray-600">
            <p>&copy; 2025 White Pages for Tradies. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
