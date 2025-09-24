@extends('layouts.public')

@section('title', 'White Pages - Find Trusted Local Tradies')

@section('content')
<section class="bg-blue-600">
    <div class="container mx-auto px-6 text-center py-24">
        <h1 class="text-5xl font-extrabold text-white leading-tight">Find Trusted Local Tradies</h1>
        <p class="mt-4 text-xl text-blue-100">Connect with qualified professionals for all your home improvement needs</p>
        <div class="mt-10 bg-white p-4 rounded-lg shadow-2xl max-w-3xl mx-auto">
            <form action="/search" method="GET" class="flex flex-col sm:flex-row gap-2">
                <select name="service" id="service-dropdown" class="flex-grow p-4 border rounded-md text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <option value="" disabled selected>Select a Service...</option>
                </select>
                <input type="text" name="location" placeholder="Enter suburb or postcode" class="flex-grow p-4 border rounded-md text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                <button type="submit" class="bg-yellow-500 text-white px-8 py-4 rounded-md font-bold hover:bg-yellow-600 transition duration-300">Search Tradies</button>
            </form>
        </div>
    </div>
</section>
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Why Choose White Pages?</h2>
        <p class="text-gray-600 mt-2 mb-12">Your trusted platform for finding reliable local tradies</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="flex flex-col items-center">
                <div class="bg-blue-100 rounded-full p-5 mb-4"><svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div>
                <h3 class="text-xl font-semibold mb-2">Easy Search</h3>
                <p class="text-gray-600">Find qualified tradies in your area with our simple search tool.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="bg-yellow-100 rounded-full p-5 mb-4"><svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <h3 class="text-xl font-semibold mb-2">Verified Reviews</h3>
                <p class="text-gray-600">Read genuine reviews from other customers to make informed decisions.</p>
            </div>
            <div class="flex flex-col items-center">
                 <div class="bg-green-100 rounded-full p-5 mb-4"><svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg></div>
                <h3 class="text-xl font-semibold mb-2">Direct Contact</h3>
                <p class="text-gray-600">Get quotes and communicate directly with tradies through our platform.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const serviceDropdown = document.getElementById('service-dropdown');
    const apiBaseUrl = "{{ env('API_BASE_URL') }}";

    try {
        const response = await fetch(`${apiBaseUrl}/api/services`);
        if (!response.ok) throw new Error('Failed to fetch services');
        const services = await response.json();
        services.forEach(service => {
            const option = document.createElement('option');
            option.value = service.name;
            option.textContent = service.name;
            serviceDropdown.appendChild(option);
        });
    } catch (error) {
        console.error('Error populating services:', error);
        const errorOption = document.createElement('option');
        errorOption.textContent = 'Could not load services';
        errorOption.disabled = true;
        serviceDropdown.appendChild(errorOption);
    }
});
</script>
@endpush
