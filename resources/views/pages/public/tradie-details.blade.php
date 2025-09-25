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
            {{-- Request Quote Card --}}
            <div id="request-quote-card" class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Request Quote</h2>

                {{-- Div để hiển thị thông báo --}}
                <div id="quote-success-message" class="hidden bg-green-100 text-green-800 p-3 rounded mb-4"></div>
                <div id="quote-error-message" class="hidden bg-red-100 text-red-800 p-3 rounded mb-4"></div>

                <form id="quote-form" class="space-y-4" novalidate>
                    <div>
                        <label for="job_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="job_description" rows="4" placeholder="Describe your job..." class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <div>
                        <label for="preferred_date" class="block text-sm font-medium text-gray-700">Preferred Date</label>
                        <input id="preferred_date" type="date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    {{-- Thêm các trường khác nếu cần, ví dụ Service Address --}}
                    <div>
                        <label for="service_address" class="block text-sm font-medium text-gray-700">Service Address</label>
                        <input id="service_address" type="text" placeholder="e.g., 123 Main St, Sydney" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                    </div>
                    <button id="quote-submit-button" type="submit" class="w-full bg-blue-600 text-white p-3 rounded-md font-semibold hover:bg-blue-700">
                        Request Quote
                    </button>
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
    let tradieProfileData = null; // Lưu profile để gửi quote
    // ...existing code...
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
    tradieProfileData = profile; // Lưu lại để dùng khi gửi quote
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

    // --- PHẦN 2: XỬ LÝ FORM GỬI QUOTE ---
    const quoteForm = document.getElementById('quote-form');
    const submitButton = document.getElementById('quote-submit-button');
    const successMessageDiv = document.getElementById('quote-success-message');
    const errorMessageDiv = document.getElementById('quote-error-message');
    const quoteCardEl = document.getElementById('request-quote-card');
    // Form field refs (needed across handlers)
    const jobDescEl = document.getElementById('job_description');
    const preferredDateEl = document.getElementById('preferred_date');
    const serviceAddressEl = document.getElementById('service_address');

    // Helpers shared by multiple handlers
    const setError = (el, hasError) => {
        if (!el) return;
        if (hasError) {
            el.classList.remove('border-gray-300');
            el.classList.add('border-red-500', 'focus:ring-red-500');
        } else {
            el.classList.remove('border-red-500', 'focus:ring-red-500');
            el.classList.add('border-gray-300');
        }
    };
    const isEmpty = (el) => !el || !String(el.value || '').trim();

    if (quoteForm) {
        quoteForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            // 0. Validate fields and outline in red if empty
            const hasJobDescError = isEmpty(jobDescEl);
            const hasPreferredDateError = isEmpty(preferredDateEl);
            const hasServiceAddressError = isEmpty(serviceAddressEl);
            setError(jobDescEl, hasJobDescError);
            setError(preferredDateEl, hasPreferredDateError);
            setError(serviceAddressEl, hasServiceAddressError);

            if (hasJobDescError || hasPreferredDateError || hasServiceAddressError) {
                errorMessageDiv.textContent = 'Please fill in all required fields.';
                errorMessageDiv.classList.remove('hidden');
                return;
            }

            // 1. Kiểm tra đăng nhập (cả localStorage và sessionStorage)
            const token = localStorage.getItem('token') || sessionStorage.getItem('token');
            const userString = localStorage.getItem('user') || sessionStorage.getItem('user');
            if (!token || !userString) {
                // Persist draft so user can continue after login
                try {
                    const draft = {
                        job_description: jobDescEl.value,
                        preferred_date: preferredDateEl.value,
                        service_address: serviceAddressEl.value
                    };
                    console.log('Saving draft before redirect:', draft);
                    sessionStorage.setItem('quote_draft', JSON.stringify(draft));
                } catch (e) {
                    console.error('Error saving draft:', e);
                }

                // Build redirect back to this page and section
                const redirectPath = `${window.location.pathname}${window.location.search}#request-quote`;
                try {
                    sessionStorage.setItem('post_login_redirect', redirectPath);
                    console.log('Saving redirect path:', redirectPath);
                } catch (e) {
                    console.error('Error saving redirect path:', e);
                }

                // Show a short inline message then redirect to login (no alert)
                errorMessageDiv.textContent = 'Please login to request a quote. Redirecting to login…';
                errorMessageDiv.classList.remove('hidden');
                submitButton.disabled = true;
                submitButton.textContent = 'Redirecting…';
                const loginUrl = `/login?redirect=${encodeURIComponent(redirectPath)}`;
                setTimeout(() => {
                    window.location.href = loginUrl;
                }, 1200);
                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';
            successMessageDiv.classList.add('hidden');
            errorMessageDiv.classList.add('hidden');

            try {
                // 2. Thu thập dữ liệu
                const resident = JSON.parse(userString);
                const payload = {
                    job_description: jobDescEl.value,
                    preferred_date: preferredDateEl.value,
                    service_address: serviceAddressEl.value,
                    resident_account_id: resident.id,
                    tradie_account_id: tradieProfileData?.account_id,
                    service_postcode: tradieProfileData?.postcode
                };

                // 3. Gọi API tạo quote
                const response = await fetch(`${apiBaseUrl}/api/quotes`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Failed to send request.');
                }

                // 4. Thành công
                successMessageDiv.textContent = 'Your quote request has been sent successfully!';
                successMessageDiv.classList.remove('hidden');
                quoteForm.reset();

            } catch (error) {
                errorMessageDiv.textContent = error.message;
                errorMessageDiv.classList.remove('hidden');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Request Quote';
            }
        });
        // Clear error highlight on input
        [jobDescEl, preferredDateEl, serviceAddressEl].forEach((el) => {
            if (!el) return;
            el.addEventListener('input', () => {
                const empty = !String(el.value || '').trim();
                setError(el, empty);
                if (!empty) errorMessageDiv.classList.add('hidden');
            });
        });

        // If returning from login, restore draft and bring form into view
        try {
            const draftString = sessionStorage.getItem('quote_draft');
            console.log('Draft restoration - found draft:', draftString);
            if (draftString) {
                const draft = JSON.parse(draftString);
                console.log('Parsed draft:', draft);
                if (draft && typeof draft === 'object') {
                    if (jobDescEl && draft.job_description) {
                        jobDescEl.value = draft.job_description;
                        console.log('Restored job_description:', draft.job_description);
                    }
                    if (preferredDateEl && draft.preferred_date) {
                        preferredDateEl.value = draft.preferred_date;
                        console.log('Restored preferred_date:', draft.preferred_date);
                    }
                    if (serviceAddressEl && draft.service_address) {
                        serviceAddressEl.value = draft.service_address;
                        console.log('Restored service_address:', draft.service_address);
                    }
                    // Show a brief message that draft was restored
                    successMessageDiv.textContent = 'Your previous form data has been restored. You can continue with your quote request.';
                    successMessageDiv.classList.remove('hidden');
                    setTimeout(() => {
                        successMessageDiv.classList.add('hidden');
                    }, 3000);
                }
                sessionStorage.removeItem('quote_draft');
            }
        } catch (e) {
            console.error('Error restoring draft:', e);
        }

        if (window.location.hash === '#request-quote' && quoteCardEl) {
            quoteCardEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            if (jobDescEl) jobDescEl.focus();
        }
    }
});
</script>
@endpush
