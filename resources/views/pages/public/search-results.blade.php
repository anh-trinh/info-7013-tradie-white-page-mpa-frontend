@extends('layouts.public')

@section('title', 'Search Results')

@section('content')
<div class="container mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-gray-800">Search Results</h1>
    <p class="text-gray-600 mb-8">Found 1 tradies matching your criteria</p>
    <div class="bg-white p-4 rounded-lg shadow-md mb-8">
        <form class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Sort by</label>
                <select class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Highest Rated</option>
                    <option>Most Reviews</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Distance</label>
                <select class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Within 10km</option>
                    <option>Within 25km</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Rating</label>
                <select class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>4+ Stars</option>
                    <option>3+ Stars</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white p-2 h-10 rounded-md font-semibold hover:bg-blue-700">Apply Filters</button>
        </form>
    </div>
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-md flex flex-col sm:flex-row items-center justify-between">
            <div class="flex items-center w-full sm:w-auto">
                <div class="bg-blue-500 text-white w-16 h-16 flex-shrink-0 flex items-center justify-center rounded-full font-bold text-2xl mr-6">M</div>
                <div>
                    <h2 class="text-xl font-bold text-blue-700">Mike's Plumbing Services <span class="text-lg font-normal text-gray-600">$85/hr</span></h2>
                    <p class="text-gray-700 font-semibold">Plumbing</p>
                    <div class="flex items-center mt-1">
                        <span class="text-yellow-500">★★★★</span><span class="text-gray-300">★</span>
                        <span class="text-gray-600 ml-2">4.8 (127 reviews)</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Melbourne, VIC - 5km away</p>
                </div>
            </div>
            <a href="/tradie/1" class="bg-gray-800 text-white px-6 py-3 rounded-md font-semibold hover:bg-black transition duration-300 mt-4 sm:mt-0 w-full sm:w-auto text-center">View Profile</a>
        </div>
    </div>
</div>
@endsection
