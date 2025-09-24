@extends('layouts.public')

@section('title', 'Search Results')

@section('content')
<div class="container mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-gray-800">Search Results</h1>
    <p id="results-summary" class="text-gray-600 mb-8">Searching for tradies...</p>

    <div class="bg-white p-4 rounded-lg shadow-md mb-8">
        <form id="filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="service-filter" class="block text-sm font-medium text-gray-700">Service</label>
                <select id="service-filter" name="service" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Services</option>
                </select>
            </div>
            <div>
                <label for="location-filter" class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" id="location-filter" name="location" placeholder="Suburb or Postcode" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sort by</label>
                <select name="sort_by" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Highest Rated</option>
                    <option>Most Reviews</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Distance</label>
                <select name="distance" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Within 10km</option>
                    <option>Within 25km</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Rating</label>
                <select name="rating" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>4+ Stars</option>
                    <option>3+ Stars</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white p-2 h-10 rounded-md font-semibold hover:bg-blue-700">Apply Filters</button>
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

            resultsContainer.innerHTML = '';
            resultsSummary.textContent = `Found ${Array.isArray(tradies) ? tradies.length : 0} tradies matching your criteria`;

            if (!Array.isArray(tradies) || tradies.length === 0) {
                resultsContainer.innerHTML = '<p class="text-center text-gray-500">No suitable tradies found.</p>';
                return;
            }

            tradies.forEach(tradie => {
                const initial = (tradie.business_name && tradie.business_name.charAt(0)) || 'T';
                const name = tradie.business_name || 'N/A';
                const rate = tradie.base_rate ? `${tradie.base_rate}` : '';
                const postcode = tradie.postcode || 'N/A';
                const id = tradie.id;

                const tradieCardHtml = `
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-blue-500 text-white w-16 h-16 flex-shrink-0 flex items-center justify-center rounded-full font-bold text-2xl mr-6">${initial}</div>
                            <div>
                                <h2 class="text-xl font-bold text-blue-700">${name} <span class="text-lg font-normal text-gray-600">${rate}</span></h2>
                                <p class="text-sm text-gray-500 mt-1">${postcode}</p>
                            </div>
                        </div>
                        <a href="/tradie/${id}" class="bg-gray-800 text-white px-6 py-3 rounded-md font-semibold hover:bg-black">View Profile</a>
                    </div>
                `;
                resultsContainer.insertAdjacentHTML('beforeend', tradieCardHtml);
            });
        } catch (error) {
            console.error('Failed to fetch tradies:', error);
            resultsContainer.innerHTML = '<p class="text-center text-red-500">Failed to load results. Please try again.</p>';
        }
    }

    async function initializePage() {
        await populateServiceFilter();

        const initialParams = new URLSearchParams(window.location.search);
        const initialService = initialParams.get('service');
        const initialLocation = initialParams.get('location');

        const locationFilterInput = document.getElementById('location-filter');
        if (initialLocation) locationFilterInput.value = initialLocation;
        if (initialService) {
            setTimeout(() => { serviceFilterSelect.value = initialService; }, 100);
        }

        fetchAndDisplayTradies();
    }

    filterForm.addEventListener('submit', function(event) {
        event.preventDefault();
        fetchAndDisplayTradies();
    });

    initializePage();
});
</script>
@endpush
