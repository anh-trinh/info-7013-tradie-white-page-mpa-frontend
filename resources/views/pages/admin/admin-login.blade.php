
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        @import url('https://rsms.me/inter/inter.css');
    </style>
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="/" class="font-bold text-3xl text-white flex items-center justify-center space-x-3">
                <span class="bg-blue-600 text-white w-10 h-10 flex items-center justify-center rounded-lg font-mono">W</span>
                <span>White Pages</span>
            </a>
        </div>
        <div class="bg-slate-800 shadow-2xl rounded-lg p-10">
            <h1 class="text-center text-3xl font-bold text-white mb-2">Administrator Login</h1>
            <p class="text-center text-sm text-slate-400 mb-8">Secure access for system administrators.</p>
            <div id="error-message" class="hidden bg-red-900/50 border border-red-500 text-red-300 text-sm font-semibold p-3 rounded-md mb-6 text-center"></div>
            <form id="admin-login-form" class="space-y-6">
                <div>
                    <label for="email" class="block text-slate-300 text-sm font-bold mb-2">Admin Email</label>
                    <input type="email" id="email" name="email" required
                           class="shadow-inner appearance-none border border-slate-600 bg-slate-700 rounded-md w-full py-3 px-4 text-white leading-tight placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="admin@example.com">
                </div>
                <div>
                    <label for="password" class="block text-slate-300 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                           class="shadow-inner appearance-none border border-slate-600 bg-slate-700 rounded-md w-full py-3 px-4 text-white leading-tight placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="******************">
                </div>
                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-300">
                        Admin Login
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const form = document.getElementById('admin-login-form');
        const errorMessageDiv = document.getElementById('error-message');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            errorMessageDiv.classList.add('hidden');
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('/api/admin/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                const data = await response.json();
                if (!response.ok) {
                    errorMessageDiv.innerText = data.message || 'Login failed! Please check your credentials.';
                    errorMessageDiv.classList.remove('hidden');
                    return;
                }
                localStorage.setItem('admin_token', data.token);
                localStorage.setItem('admin_user', JSON.stringify(data.user));
                window.location.href = '/admin';
            } catch (error) {
                console.error('Login error:', error);
                errorMessageDiv.innerText = 'An unexpected error occurred. Please try again.';
                errorMessageDiv.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
