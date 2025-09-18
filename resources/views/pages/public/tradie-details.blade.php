@extends('layouts.public')

@section('title', "Mike's Plumbing Services")

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 bg-white p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800">Mike's Plumbing Services</h1>
                    <div class="flex items-center mt-2"><span class="text-yellow-500">★★★★</span><span class="text-gray-300">★</span><span class="text-gray-600 ml-2">4.8 (127 reviews)</span></div>
                    <p class="text-md text-gray-500 mt-1">Melbourne, VIC - 5km away</p>
                </div>
                <p class="text-3xl font-bold text-blue-600">$85/hour <span class="block text-sm font-normal text-gray-500 text-right">starting rate</span></p>
            </div>
            <div class="border-t pt-6 mb-8">
                <h2 class="text-2xl font-semibold mb-3">About</h2>
                <p class="text-gray-700 leading-relaxed">Licensed plumber with over 15 years of experience. Specializing in residential and commercial plumbing services. Available for emergency callouts 24/7.</p>
            </div>
            <div class="border-t pt-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">Services Offered</h2>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-gray-700 list-disc list-inside">
                    <li>Blocked Drains</li><li>Tap Repairs</li><li>Hot Water Systems</li><li>Bathroom Renovations</li><li>Gas Fitting</li><li>Emergency Repairs</li>
                </ul>
            </div>
            <div class="border-t pt-6">
                <h2 class="text-2xl font-semibold mb-4">Recent Reviews</h2>
                <div class="space-y-6">
                    <div class="border-b pb-4"><div class="flex justify-between items-center"><h3 class="font-bold">Sarah J.</h3><p class="text-sm text-gray-500">2 days ago</p></div><div class="flex items-center my-1"><span class="text-yellow-500">★★★★★</span></div><p class="text-gray-700">Excellent service! Mike was punctual, professional, and fixed our blocked drain quickly. Highly recommended!</p></div>
                    <div class="border-b pb-4"><div class="flex justify-between items-center"><h3 class="font-bold">David C.</h3><p class="text-sm text-gray-500">1 week ago</p></div><div class="flex items-center my-1"><span class="text-yellow-500">★★★★★</span></div><p class="text-gray-700">Great work on our hot water system replacement, fair pricing and quality workmanship.</p></div>
                </div>
            </div>
        </div>
        <div class="space-y-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Contact Information</h2>
                <div class="space-y-3 text-gray-700"><p><strong>Phone:</strong> (03) 9876 5432</p><p><strong>Email:</strong> mike@mikesplumbing.com.au</p><p><strong>Website:</strong> www.mikesplumbing.com.au</p></div>
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
