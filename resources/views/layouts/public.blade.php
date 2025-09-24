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
            <div id="header-logged-out">
                <a href="/login" class="text-gray-600 font-medium hover:text-blue-600 mr-6">Login</a>
                <a href="/register" class="bg-blue-600 text-white px-5 py-2 rounded-md font-semibold hover:bg-blue-700">Register</a>
            </div>

            <div id="header-logged-in" style="display: none;" class="relative">
                <button id="user-menu-button" class="flex items-center space-x-2">
                    <div id="user-avatar" class="w-10 h-10 rounded-full bg-slate-600 text-white flex items-center justify-center font-bold"></div>
                    <span id="user-name" class="font-semibold text-gray-700"></span>
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                    <a id="dashboard-link" href="/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Dashboard</a>
                    <a id="logout-button" href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                </div>
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
    @stack('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loggedOutView = document.getElementById('header-logged-out');
        const loggedInView = document.getElementById('header-logged-in');

        const token = localStorage.getItem('token') || sessionStorage.getItem('token');
        const userString = localStorage.getItem('user') || sessionStorage.getItem('user');

        if (token && userString) {
            const user = JSON.parse(userString);
            if (loggedOutView) loggedOutView.style.display = 'none';
            if (loggedInView) loggedInView.style.display = 'block';

            const userAvatar = document.getElementById('user-avatar');
            const userName = document.getElementById('user-name');
            const dashboardLink = document.getElementById('dashboard-link');

            if (userName) userName.textContent = user.first_name || 'User';
            if (userAvatar) userAvatar.textContent = (user.first_name ? user.first_name.charAt(0) : 'U').toUpperCase();
            if (dashboardLink) dashboardLink.href = (user.role === 'tradie') ? '/tradie-dashboard' : '/dashboard';

            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });
            }

            const logoutButton = document.getElementById('logout-button');
            if (logoutButton) {
                logoutButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    try {
                        localStorage.removeItem('token');
                        localStorage.removeItem('user');
                        sessionStorage.removeItem('token');
                        sessionStorage.removeItem('user');
                    } catch (e) {}
                    window.location.reload();
                });
            }
        }
    });
    </script>
</body>
</html>
