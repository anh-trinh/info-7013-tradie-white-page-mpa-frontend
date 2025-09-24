@extends('layouts.public')

@section('title', 'Sign In to Your Account')

@section('content')
<div class="container mx-auto max-w-md py-16 px-6">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-center text-gray-800">Sign in to your account</h1>
        <p class="text-center text-gray-600 mb-8">Welcome back to White Pages</p>
    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert"></div>
    <div id="success-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert"></div>
        <form id="login-form" novalidate>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" type="email" placeholder="Email" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" type="password" placeholder="******************" required>
            </div>
            <div class="flex items-center justify-between mb-6">
                <label for="remember" class="flex items-center text-sm text-gray-600">
                    <input id="remember" name="remember" class="mr-2 leading-tight" type="checkbox">
                    <span class="text-sm">Remember me</span>
                </label>
                <a class="inline-block align-baseline font-semibold text-sm text-blue-600 hover:text-blue-800" href="#">
                    Forgot Password?
                </a>
            </div>
            <div class="flex items-center justify-center">
                <button id="submit-button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:shadow-outline w-full" type="submit">Sign In</button>
            </div>
             <p class="text-center text-sm text-gray-600 mt-6">
                Don't have an account? <a href="/register" class="text-blue-600 hover:underline font-semibold">Register</a>
            </p>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('login-form');
    const submitButton = document.getElementById('submit-button');
    const successMessageDiv = document.getElementById('success-message');
    const errorMessageDiv = document.getElementById('error-message');
    const apiBaseUrl = "{{ env('API_BASE_URL') }}";

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        submitButton.disabled = true;
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Signing In...';
        successMessageDiv.classList.add('hidden');
        errorMessageDiv.classList.add('hidden');

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            const remember = document.getElementById('remember') ? document.getElementById('remember').checked : false;
            const response = await fetch(`${apiBaseUrl}/api/accounts/login`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password, remember })
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                throw new Error(data.message || 'Incorrect email or password.');
            }

            // Choose storage based on remember flag
            const storage = remember ? window.localStorage : window.sessionStorage;
            // Clear both to avoid stale state
            try {
                window.localStorage.removeItem('token');
                window.localStorage.removeItem('user');
                window.sessionStorage.removeItem('token');
                window.sessionStorage.removeItem('user');
            } catch (e) {}
            storage.setItem('token', data.token);
            storage.setItem('user', JSON.stringify(data.user));

            if (data.user && data.user.role === 'resident') {
                window.location.href = '/dashboard';
            } else if (data.user && data.user.role === 'tradie') {
                window.location.href = '/tradie-dashboard';
            } else if (data.user && data.user.role === 'admin') {
                window.location.href = '/admin';
            } else {
                window.location.href = '/';
            }

        } catch (error) {
            errorMessageDiv.textContent = error.message || 'An unexpected error occurred. Please try again.';
            errorMessageDiv.classList.remove('hidden');
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    });
});
</script>
@endsection
