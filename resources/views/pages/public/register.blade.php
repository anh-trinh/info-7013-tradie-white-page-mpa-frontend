@extends('layouts.public')

@section('title', 'Create Your Account')

@section('content')
<div class="container mx-auto max-w-2xl py-12 px-6">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-center text-gray-800">Create your account</h1>
        <p class="text-center text-gray-600 mb-8">Join White Pages today</p>
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
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-md focus:outline-none focus:shadow-outline w-full" type="submit">
                    Create Account
                </button>
            </div>
            <p class="text-center text-sm text-gray-600 mt-6">
                Already have an account? <a href="/login" class="text-blue-600 hover:underline font-semibold">Login</a>
            </p>
        </form>
    </div>
</div>
<script>
    document.getElementById('register-form').addEventListener('submit', async function(event) {
        event.preventDefault();
        // Add logic to call /api/accounts/register here
        // After successful registration, show message and redirect
        alert('Registration successful! Please login.');
        window.location.href = '/login';
    });
</script>
@endsection
