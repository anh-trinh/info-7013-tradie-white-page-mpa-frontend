@extends('layouts.public')

@section('title', 'Search Results')

@php
function renderStarsForJs(float $rating, int $reviewCount) {
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
    $starSvg = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>';
    
    $html = '<div class="flex items-center mt-1">';
    for ($i = 0; $i < $fullStars; $i++) $html .= str_replace('class="w-5 h-5"', 'class="w-5 h-5 text-yellow-400"', $starSvg);
    if ($halfStar) $html .= str_replace('class="w-5 h-5"', 'class="w-5 h-5 text-yellow-400"', $starSvg);
    for ($i = 0; $i < $emptyStars; $i++) $html .= str_replace('class="w-5 h-5"', 'class="w-5 h-5 text-gray-300"', $starSvg);
    
    if ($reviewCount > 0) {
         $html .= '<span class="text-gray-600 ml-2 text-sm">' . number_format($rating, 1) . ' (' . $reviewCount . ' reviews)</span>';
    } else {
         $html .= '<span class="text-gray-600 ml-2 text-sm">No reviews yet</span>';
    }
    $html .= '</div>';
    return $html;
}
@endphp

@section('content')
<div class="container mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-gray-800">Search Results</h1>
    <p id="results-summary" class="text-gray-600 mb-8">Searching for tradies...</p>

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-lg shadow-md mb-8">
        <form id="filter-form" class="flex flex-col md:flex-row md:items-end gap-4">
            
            {{-- Location Filter --}}
            <div class="flex-grow">
                <label for="location-filter" class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" id="location-filter" name="location" placeholder="Suburb or Postcode" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>

            {{-- Service Filter --}}
            <div class="flex-grow">
                <label for="service-filter" class="block text-sm font-medium text-gray-700">Service</label>
                <select id="service-filter" name="service" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </select>
            </div>
            
            {{-- Sort by Filter --}}
            <div class="flex-grow">
                <label for="sort-by-filter" class="block text-sm font-medium text-gray-700">Sort by</label>
                <select id="sort-by-filter" name="sort_by" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Highest Rated</option>
                    <option>Most Reviews</option>
                </select>
            </div>

            {{-- Rating Filter --}}
            <div class="flex-grow">
                <label for="rating-filter" class="block text-sm font-medium text-gray-700">Rating</label>
                <select id="rating-filter" name="rating" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Stars</option>
                    <option value="1">1+ Stars</option>
                    <option value="2">2+ Stars</option>
                    <option value="3">3+ Stars</option>
                    <option value="4">4+ Stars</option>
                    <option value="5">5 Stars</option>
                </select>
            </div>

            {{-- Apply Button --}}
            <div class="flex-shrink-0">
                <button type="submit" class="w-full bg-blue-600 text-white p-2 h-10 rounded-md font-semibold hover:bg-blue-700">Apply Filters</button>
            </div>

        </form>
    </div>
    <div id="results-container" class="space-y-6">
        <p id="loading-message" class="text-center text-gray-500">Loading tradies...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiBaseUrl = "{{ env('API_BASE_URL') }}";
    const filterForm = document.getElementById('filter-form');
    const serviceFilterSelect = document.getElementById('service-filter');
    const resultsContainer = document.getElementById('results-container');
    const resultsSummary = document.getElementById('results-summary');
    const sortBySelect = document.getElementById('sort-by-filter');
    const ratingFilterSelect = document.getElementById('rating-filter');

    // Client-side star renderer (keeps behavior consistent with tradie details)
    function renderStarRating(rating = 0, reviewCount = 0, showText = true) {
        const r = Math.min(5, Math.max(0, Number(rating) || 0));
        const count = Number(reviewCount) || 0;
        const full = Math.floor(r);
        const half = (r - full) >= 0.5 ? 1 : 0;
        const empty = 5 - full - half;

        const starPath = 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z';
        const fullStar = () => `<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="${starPath}" /></svg>`;
        const emptyStar = () => `<svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="${starPath}" /></svg>`;
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

    async function populateServiceFilter() {
        try {
            const response = await fetch(`${apiBaseUrl}/api/services`);
            if (!response.ok) throw new Error('Failed to fetch services');
            const services = await response.json();
            services.forEach(service => {
                const option = document.createElement('option');
                option.value = service.name;
                option.textContent = service.name;
                serviceFilterSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Failed to fetch services:', error);
        }
    }

    async function fetchAndDisplayTradies() {
        resultsContainer.innerHTML = '<p class="text-center text-gray-500">Loading tradies...</p>';

        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        const initialParams = new URLSearchParams(window.location.search);
        if (!params.get('service') && initialParams.get('service')) {
            params.set('service', initialParams.get('service'));
        }
        if (!params.get('location') && initialParams.get('location')) {
            params.set('location', initialParams.get('location'));
        }

        try {
            const response = await fetch(`${apiBaseUrl}/api/tradies?${params.toString()}`);
            if (!response.ok) throw new Error('Failed to fetch tradies');
            const tradies = await response.json();

            console.log('Full API response:', tradies); // Debug: toàn bộ response
            console.log('First tradie data:', tradies[0]); // Debug: tradie đầu tiên
            
            resultsContainer.innerHTML = '';
            resultsSummary.textContent = `Found ${Array.isArray(tradies) ? tradies.length : 0} tradies matching your criteria`;

            if (!Array.isArray(tradies) || tradies.length === 0) {
                resultsContainer.innerHTML = '<p class="text-center text-gray-500">No suitable tradies found.</p>';
                return;
            }

            // Enrich all tradies with rating/reviewCount first
            const enrichedPromises = tradies.map(async (tradie) => {
                const initial = (tradie.business_name && tradie.business_name.charAt(0)) || 'T';
                const name = tradie.business_name || 'N/A';
                const rate = tradie.base_rate ? `$${tradie.base_rate}/hour` : 'Rate on enquiry';
                const postcode = tradie.postcode || 'N/A';
                const locationDisplay = postcode !== 'N/A' ? `Location: ${postcode}` : 'Location: Not specified';
                const id = tradie.id;
                
                // Use backend aggregation only (drop extra reviews fetch)
                const rating = parseFloat(tradie.average_rating ?? 0);
                const reviewCount = parseInt(tradie.reviews_count ?? 0);
                return { id, name, initial, rate, locationDisplay, rating, reviewCount };
            });
            
            // Wait for enrichment
            const enriched = await Promise.all(enrichedPromises);

            // Apply rating filter
            const ratingFilterValue = ratingFilterSelect?.value || 'all';
            const minRating = ratingFilterValue === 'all' ? 0 : Number(ratingFilterValue);
            const filtered = enriched.filter(item => (item.rating || 0) >= minRating);

            // Sort
            const sortBy = sortBySelect?.value || 'Highest Rated';
            filtered.sort((a, b) => {
                if (sortBy === 'Most Reviews') {
                    if (b.reviewCount !== a.reviewCount) return b.reviewCount - a.reviewCount;
                    return (b.rating || 0) - (a.rating || 0);
                }
                // Highest Rated
                if ((b.rating || 0) !== (a.rating || 0)) return (b.rating || 0) - (a.rating || 0);
                return b.reviewCount - a.reviewCount;
            });

            // Render
            if (filtered.length === 0) {
                resultsContainer.innerHTML = '<p class="text-center text-gray-500">No suitable tradies found.</p>';
            } else {
                filtered.forEach(({ id, name, initial, rate, locationDisplay, rating, reviewCount }) => {
                    const cardHtml = `
                        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-blue-500 text-white w-16 h-16 flex-shrink-0 flex items-center justify-center rounded-full font-bold text-2xl mr-6">${initial}</div>
                                <div>
                                    <h2 class="text-xl font-bold text-blue-700">${name} <span class="text-lg font-normal text-gray-600">${rate}</span></h2>
                                    ${renderStarRating(rating, reviewCount, true)}
                                    <p class="text-sm text-gray-500 mt-1">${locationDisplay}</p>
                                </div>
                            </div>
                            <a href="/tradie/${id}" class="bg-gray-800 text-white px-6 py-3 rounded-md font-semibold hover:bg-black">View Profile</a>
                        </div>
                    `;
                    resultsContainer.insertAdjacentHTML('beforeend', cardHtml);
                });
            }
        } catch (error) {
            console.error('Failed to fetch tradies:', error);
            resultsContainer.innerHTML = '<p class="text-center text-red-500">Failed to load results. Please try again.</p>';
        }
    }

    async function initializePage() {
        const initialParams = new URLSearchParams(window.location.search);
        const initialService = initialParams.get('service');
        const initialLocation = initialParams.get('location');

        // First populate services
        await populateServiceFilter();

        // Sync initial location
        const locationFilterInput = document.getElementById('location-filter');
        if (initialLocation) locationFilterInput.value = initialLocation;

        // Ensure the service exists then select it; if missing, add it so selection persists
        if (initialService) {
            const hasOption = Array.from(serviceFilterSelect.options).some(opt => opt.value === initialService);
            if (!hasOption) {
                const opt = document.createElement('option');
                opt.value = initialService;
                opt.textContent = initialService;
                serviceFilterSelect.appendChild(opt);
            }
            serviceFilterSelect.value = initialService;
        }

        fetchAndDisplayTradies();
    }

    filterForm.addEventListener('submit', function(event) {
        event.preventDefault();
        fetchAndDisplayTradies();
    });

    // Re-fetch on sort/rating change for better UX
    sortBySelect.addEventListener('change', fetchAndDisplayTradies);
    ratingFilterSelect.addEventListener('change', fetchAndDisplayTradies);

    initializePage();
});
</script>
@endpush
