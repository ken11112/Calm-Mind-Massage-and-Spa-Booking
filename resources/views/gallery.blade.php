@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
<div class="py-6 sm:py-12">
    <h2 class="text-center text-gray-900 text-xl sm:text-2xl lg:text-3xl font-medium mb-4 sm:mb-6">My Big Adventure</h2>

    @php
        // compute the first image URL to show server-side so browser doesn't show broken icon
        $firstImage = '';
        if(isset($galleries) && $galleries->count() > 0) {
            $firstImage = asset('storage/' . $galleries->first()->image_path);
        } elseif(isset($staticFiles) && count($staticFiles) > 0) {
            $firstImage = asset($staticFiles[0]);
        }
        // Debug output
        if(empty($firstImage)) {
            echo '<!-- No images found in galleries or staticFiles -->';
        }
    @endphp

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    @include('partials.gallery-albums', ['albums' => $albums, 'staticFiles' => $staticFiles ?? []])
    </div>

    <!-- Booking CTA -->
    <div class="mt-6 sm:mt-8 text-center">
        <a href="{{ route('booking.create') }}" class="inline-block bg-white text-black font-bold py-2 sm:py-3 px-6 sm:px-8 rounded-full hover:bg-gray-100 transition duration-200 min-h-[44px] sm:min-h-[48px] flex items-center justify-center">
            Book Your Session Now
        </a>
    </div>
</div>

@endsection
