@extends('layouts.public')

@section('title', 'Tradie Details')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 bg-white p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 id="tradie-name" class="text-4xl font-bold text-gray-800">Loading...</h1>
                    <div id="tradie-rating" class="flex items-center mt-2 text-gray-600"></div>
                    <p id="tradie-location" class="text-md text-gray-500 mt-1"></p>
                </div>
                <p id="tradie-rate" class="text-3xl font-bold text-blue-600"></p>
            </div>
            <div class="border-t pt-6 mb-8">
                <h2 class="text-2xl font-semibold mb-3">About</h2>
                <p id="tradie-about" class="text-gray-700 leading-relaxed"></p>
            </div>
            <div class="border-t pt-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">Services Offered</h2>
                <ul id="services-list" class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-gray-700 list-disc list-inside"></ul>
            </div>
            <div class="border-t pt-6">
                <h2 class="text-2xl font-semibold mb-4">Recent Reviews</h2>
                <div id="reviews-container" class="space-y-6"><p>Loading reviews...</p></div>
            </div>
        </div>
        <div class="space-y-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Contact Information</h2>
                <div class="space-y-3 text-gray-700">
                    <p><strong>Phone:</strong> <span id="tradie-phone"></span></p>
                    <p><strong>Email:</strong> <span id="tradie-email"></span></p>
                    <p><strong>Website:</strong> <span id="tradie-website"></span></p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Request Quote</h2>
                <form class="space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700">Service Needed</label><select class="mt-1 block w-full p-2 border border-gray-300 rounded-md"><option>Blocked Drain</option><option>Hot Water System</option></select></div>
                    <div><label class="block text-sm font-medium text-gray-700">Description</label><textarea rows="4" placeholder="Describe your job..." class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea></div>
                    <div><label class="block text-sm font-medium text-gray-700">Preferred Date</label><input type="date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></div>
                    <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-md font-semibold hover:bg-blue-700">Request Quote</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const tradieId = {{ $id }};
    const apiBaseUrl = "{{ env('API_BASE_URL') }}";

    const nameEl = document.getElementById('tradie-name');
    const ratingEl = document.getElementById('tradie-rating');
    const locationEl = document.getElementById('tradie-location');
    const rateEl = document.getElementById('tradie-rate');
    const aboutEl = document.getElementById('tradie-about');
    const servicesList = document.getElementById('services-list');
    const reviewsContainer = document.getElementById('reviews-container');
    const phoneEl = document.getElementById('tradie-phone');
    const emailEl = document.getElementById('tradie-email');
    const websiteEl = document.getElementById('tradie-website');

    try {
        const [profileRes, reviewsRes] = await Promise.all([
            fetch(`${apiBaseUrl}/api/tradies/${tradieId}`),
            fetch(`${apiBaseUrl}/api/reviews/tradie/${tradieId}`)
        ]);

        if (!profileRes.ok || !reviewsRes.ok) {
            throw new Error('Failed to fetch tradie details');
        }

        const profile = await profileRes.json();
        const reviews = await reviewsRes.json();

        nameEl.innerText = profile.business_name || 'Tradie Profile';
        aboutEl.innerText = profile.about || 'No description available.';
        locationEl.innerText = profile.location || '';
        rateEl.innerHTML = profile.base_rate ? `${profile.base_rate} <span class="block text-sm font-normal text-gray-500 text-right">starting rate</span>` : '';

        phoneEl.innerText = profile.phone || '';
        emailEl.innerText = profile.email || '';
        websiteEl.innerText = profile.website || '';

        servicesList.innerHTML = '';
        if (Array.isArray(profile.categories) && profile.categories.length) {
            profile.categories.forEach(cat => {
                servicesList.insertAdjacentHTML('beforeend', `<li>${cat.name}</li>`);
            });
        } else {
            servicesList.innerHTML = '<li>No services listed.</li>';
        }

        ratingEl.innerHTML = '';
        const rating = Number(profile.rating || 0);
        const stars = '★'.repeat(Math.round(rating)) + '☆'.repeat(5 - Math.round(rating));
        ratingEl.innerHTML = `<span class="text-yellow-500">${stars}</span>${profile.reviews_count ? `<span class=\"text-gray-600 ml-2\">${rating.toFixed(1)} (${profile.reviews_count} reviews)</span>` : ''}`;

        reviewsContainer.innerHTML = '';
        if (Array.isArray(reviews) && reviews.length) {
            reviews.forEach(review => {
                const date = review.created_at ? new Date(review.created_at).toLocaleDateString() : '';
                const rs = '★'.repeat(review.rating || 0) + '☆'.repeat(5 - (review.rating || 0));
                reviewsContainer.insertAdjacentHTML('beforeend', `
                    <div class="border-b pb-4">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold">${review.reviewer_name || 'Anonymous'}</h3>
                            <p class="text-sm text-gray-500">${date}</p>
                        </div>
                        <div class="flex items-center my-1"><span class="text-yellow-500">${rs}</span></div>
                        <p class="text-gray-700">${review.comment || ''}</p>
                    </div>
                `);
            });
        } else {
            reviewsContainer.innerHTML = '<p>No reviews yet.</p>';
        }

    } catch (err) {
        console.error('Error loading tradie details:', err);
        nameEl.innerText = 'Could not load profile.';
    }
});
</script>
@endpush
