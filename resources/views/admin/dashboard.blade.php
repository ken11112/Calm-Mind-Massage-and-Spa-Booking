@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Bookings -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-500 rounded-full">
                <i class="fas fa-calendar text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Total Bookings</h3>
                <p class="text-2xl font-semibold">{{ $totalBookings ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Today's Bookings -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-500 rounded-full">
                <i class="fas fa-clock text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Today's Bookings</h3>
                <p class="text-2xl font-semibold">{{ $todayBookings ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Total Services -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-500 rounded-full">
                <i class="fas fa-spa text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Active Services</h3>
                <p class="text-2xl font-semibold">{{ $activeServices }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold">Recent Bookings</h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentBookings as $booking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->client_name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->contact_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $booking->service->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ optional($booking->booking_datetime)->format('M d, Y') ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ optional($booking->booking_datetime)->format('h:i A') ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ₱{{ number_format($booking->price, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection