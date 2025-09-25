@extends('layouts.public')

@section('title', 'Tradie Details')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 bg-white p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 id="tradie-name" class="text-4xl font-bold text-gray-800">Loading...</h1>
                    <div id="tradie-rating-container" class="mt-2">
                        {{-- Rating will be populated by JavaScript --}}
                    </div>
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
                    <p><strong>Phone:</strong> <span id="tradie-phone">Loading...</span></p>
                    <p><strong>Email:</strong> <span id="tradie-email">Loading...</span></p>
                    <p id="tradie-website-container" style="display: none;"><strong>Website:</strong> <span id="tradie-website"></span></p>
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

    // Helper to render star rating entirely on the client side (fixes server-side placeholder issue)
    function renderStarRating(rating = 0, reviewCount = 0, showText = true) {
        const r = Math.min(5, Math.max(0, Number(rating) || 0));
        const count = Number(reviewCount) || 0;
        const full = Math.floor(r);
        const half = (r - full) >= 0.5 ? 1 : 0;
        const empty = 5 - full - half;

        const starPath = 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z';

        const fullStar = () => `<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="${starPath}" /></svg>`;
        const emptyStar = () => `<svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="${starPath}" /></svg>`;

        // Half star using clipPath to fill 50% with yellow, rest gray
        const halfStarId = `halfClip-${Math.random().toString(36).slice(2)}`;
        const halfStar = () => `
            <svg class="w-5 h-5" viewBox="0 0 20 20">
              <defs>
                <clipPath id="${halfStarId}"><rect x="0" y="0" width="10" height="20"/></clipPath>
              </defs>
              <path d="${starPath}" fill="#D1D5DB"></path>
              <g clip-path="url(#${halfStarId})">
                <path d="${starPath}" fill="#FBBF24"></path>
              </g>
            </svg>`;

        let starsHtml = '';
        for (let i = 0; i < full; i++) starsHtml += fullStar();
        if (half) starsHtml += halfStar();
        for (let i = 0; i < empty; i++) starsHtml += emptyStar();

        let textHtml = '';
        if (showText) {
            if (r > 0 && count > 0) {
                const reviewWord = count === 1 ? 'review' : 'reviews';
                textHtml = `<span class="text-gray-600 ml-2 text-sm">${r.toFixed(1)} (${count} ${reviewWord})</span>`;
            } else if (r > 0) {
                textHtml = `<span class="text-gray-600 ml-2 text-sm">${r.toFixed(1)} (No reviews yet)</span>`;
            } else {
                textHtml = `<span class="text-gray-600 ml-2 text-sm">No reviews yet</span>`;
            }
        }

        return `<div class="flex items-center mt-1">${starsHtml}${textHtml}</div>`;
    }

    const nameEl = document.getElementById('tradie-name');
    const ratingContainer = document.getElementById('tradie-rating-container');
    const locationEl = document.getElementById('tradie-location');
    const rateEl = document.getElementById('tradie-rate');
    const aboutEl = document.getElementById('tradie-about');
    const servicesList = document.getElementById('services-list');
    const reviewsContainer = document.getElementById('reviews-container');
    const phoneEl = document.getElementById('tradie-phone');
    const emailEl = document.getElementById('tradie-email');
    const websiteEl = document.getElementById('tradie-website');

    try {
        console.log(`Fetching data for tradie ID: ${tradieId}`);
        console.log(`Profile URL: ${apiBaseUrl}/api/tradies/${tradieId}`);
        console.log(`Reviews URL: ${apiBaseUrl}/api/reviews/tradie/${tradieId}`);
        
        const [profileRes, reviewsRes] = await Promise.all([
            fetch(`${apiBaseUrl}/api/tradies/${tradieId}`),
            fetch(`${apiBaseUrl}/api/reviews/tradie/${tradieId}`)
        ]);

        console.log('Profile response status:', profileRes.status);
        console.log('Reviews response status:', reviewsRes.status);

        if (!profileRes.ok || !reviewsRes.ok) {
            console.error('API Error:', profileRes.status, reviewsRes.status);
            throw new Error('Failed to fetch tradie details');
        }

        const profile = await profileRes.json();
        const reviews = await reviewsRes.json();

        console.log('Full Profile data:', profile);
        console.log('Available profile keys:', Object.keys(profile));
        console.log('Reviews data:', reviews);
        console.log('Profile rating field:', profile.average_rating);
        console.log('Profile rating field (alt):', profile.rating);
        console.log('Profile review count field:', profile.reviews_count);
        console.log('Profile review count field (alt):', profile.review_count);

        nameEl.innerText = profile.business_name || 'Could not load profile';
        
        // Get real rating data from API - now using backend aggregation  
        let profileRating = parseFloat(profile.average_rating || 0);
        let profileReviewCount = parseInt(profile.reviews_count || 0);
        
        console.log(`Backend provided: rating=${profileRating}, count=${profileReviewCount}`);
        
        // Fallback: If backend aggregation is 0 but we have reviews, calculate from reviews
        if (profileRating === 0 && profileReviewCount === 0 && Array.isArray(reviews) && reviews.length > 0) {
            const totalRating = reviews.reduce((sum, review) => sum + (review.rating || 0), 0);
            profileRating = totalRating / reviews.length;
            profileReviewCount = reviews.length;
            console.log(`Calculated from reviews: rating=${profileRating.toFixed(1)}, count=${profileReviewCount}`);
        }
        
        console.log(`Final values: rating=${profileRating}, count=${profileReviewCount}`);
        
    // Render rating with client-side generator to avoid server-side placeholder evaluation
    ratingContainer.innerHTML = renderStarRating(profileRating, profileReviewCount, true);
        aboutEl.innerText = profile.about || profile.description || 'No description available.';
        
        // Format location with label
        const location = profile.location || profile.postcode || '';
        locationEl.innerText = location ? `Location: ${location}` : 'Location: Not specified';
        
        rateEl.innerHTML = profile.base_rate ? `$${profile.base_rate}/hour <span class="block text-sm font-normal text-gray-500 text-right">starting rate</span>` : 'Rate on enquiry';

        phoneEl.innerText = profile.phone_number || 'N/A';
        emailEl.innerText = profile.email || 'N/A';
        
        const websiteContainer = document.getElementById('tradie-website-container');
        if (profile.website && profile.website.trim() !== '') {
            websiteEl.innerText = profile.website;
            websiteContainer.style.display = 'block';
        } else {
            websiteContainer.style.display = 'none';
        }

        servicesList.innerHTML = '';
        if (Array.isArray(profile.categories) && profile.categories.length) {
            profile.categories.forEach(cat => {
                servicesList.insertAdjacentHTML('beforeend', `<li>${cat.name}</li>`);
            });
        } else {
            servicesList.innerHTML = '<li>No services listed.</li>';
        }

        // Display reviews - match logic with rating display
        reviewsContainer.innerHTML = '';
        console.log('Reviews data type:', typeof reviews);
        console.log('Reviews is array:', Array.isArray(reviews));
        console.log('Reviews length:', reviews?.length);
        console.log('Reviews content:', reviews);
        console.log('Profile review count for consistency:', profileReviewCount);
        
        // Use consistent logic with rating display
        if (Array.isArray(reviews) && reviews.length > 0) {
            console.log('Processing real reviews from API:', reviews.length);
            reviews.forEach((review, index) => {
                console.log(`Review ${index}:`, review);
                const date = review.created_at ? new Date(review.created_at).toLocaleDateString() : '';
                const reviewRatingHtml = renderStarRating(review.rating || 0, 0, false);
                    
                const reviewHtml = `
                    <div class="border-b pb-4 mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold">${review.reviewer_name || review.name || 'Anonymous'}</h3>
                            <p class="text-sm text-gray-500">${date}</p>
                        </div>
                        ${reviewRatingHtml}
                        <p class="text-gray-700 mt-2">${review.comment || review.review || review.text || 'No comment'}</p>
                    </div>
                `;
                console.log('Generated review HTML for real review:', reviewHtml);
                reviewsContainer.insertAdjacentHTML('beforeend', reviewHtml);
            });
        } else {
            // Match the "No reviews yet" message with rating display for consistency
            console.log('No reviews available - using consistent message with rating display');
            reviewsContainer.innerHTML = '<p class="text-gray-500 italic">No reviews yet</p>';
        }

    } catch (err) {
        console.error('Error loading tradie details:', err);
        nameEl.innerText = 'Could not load profile.';
    }
});
</script>
@endpush
