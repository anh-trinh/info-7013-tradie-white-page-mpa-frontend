@extends('layouts.public')

@section('title', 'Create Your Account')

@section('content')
<div class="container mx-auto max-w-2xl py-12 px-6">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-center text-gray-800">Create your account</h1>
        <p class="text-center text-gray-600 mb-8">Join White Pages today</p>
        <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert"></div>
        <div id="success-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert"></div>
        <form id="register-form" novalidate>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Account Type</label>
                <div class="flex gap-4">
                    <label class="flex-1 border p-4 rounded-md cursor-pointer has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                        <input type="radio" name="role" value="resident" class="mr-2" checked> Resident (I need services)
                    </label>
                    <label class="flex-1 border p-4 rounded-md cursor-pointer has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                        <input type="radio" name="role" value="tradie" class="mr-2"> Tradie (I provide services)
                    </label>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">First Name</label>
                    <input class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="first_name" type="text" placeholder="John">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">Last Name</label>
                    <input class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="last_name" type="text" placeholder="Doe">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" type="email" placeholder="you@example.com">
            </div>
             <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">Phone</label>
                <input class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="phone" type="tel" placeholder="0412 345 678">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" type="password" placeholder="******************">
            </div>
            <div class="flex items-center justify-center">
                <button id="submit-button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-md focus:outline-none focus:shadow-outline w-full" type="submit">Create Account</button>
            </div>
            <p class="text-center text-sm text-gray-600 mt-6">
                Already have an account? <a href="/login" class="text-blue-600 hover:underline font-semibold">Login</a>
            </p>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-form');
    const submitButton = document.getElementById('submit-button');
    const successMessageDiv = document.getElementById('success-message');
    const errorMessageDiv = document.getElementById('error-message');
    const apiBaseUrl = "{{ env('API_BASE_URL') }}";

    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        submitButton.disabled = true;
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Creating Account...';
        successMessageDiv.classList.add('hidden');
        errorMessageDiv.classList.add('hidden');

        const formData = {
            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            password: document.getElementById('password').value,
            role: document.querySelector('input[name="role"]:checked').value
        };

        try {
            const response = await fetch(`${apiBaseUrl}/api/accounts/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                let errorText = data.message || 'Registration failed. Please check your details.';
                if (data.errors && typeof data.errors === 'object') {
                    const firstKey = Object.keys(data.errors)[0];
                    if (firstKey && Array.isArray(data.errors[firstKey]) && data.errors[firstKey][0]) {
                        errorText = data.errors[firstKey][0];
                    }
                } else if (data.email && Array.isArray(data.email) && data.email[0]) {
                    errorText = data.email[0];
                }
                throw new Error(errorText);
            }

            successMessageDiv.textContent = 'Account created successfully! Redirecting to login...';
            successMessageDiv.classList.remove('hidden');
            setTimeout(() => { window.location.href = '/login'; }, 3000);

        } catch (error) {
            errorMessageDiv.textContent = error.message || 'An unexpected error occurred.';
            errorMessageDiv.classList.remove('hidden');
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    });
});
</script>
@endsection
