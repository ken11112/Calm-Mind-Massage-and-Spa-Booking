@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="space-y-12">
    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-400 to-blue-500 opacity-40 blur-2xl"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 items-center">
                <div class="space-y-6 order-2 lg:order-1">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight">Relax your body, clear your mind</h1>
                    <p class="text-base sm:text-lg text-slate-700 max-w-xl">At Calm Mind Massage, we blend expert touch with serene atmosphere to bring you tailored treatments that restore balance and wellbeing.</p>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 mt-6">
                        <a href="{{ route('booking.create') }}" class="cta-btn inline-flex items-center justify-center space-x-3 shadow-lg w-full sm:w-auto">
                            <i class="fas fa-calendar-check"></i>
                            <span>Book Appointment</span>
                        </a>

                        <a href="{{ route('gallery') }}" class="px-4 py-2 sm:py-3 rounded-full bg-white text-slate-800 font-semibold shadow hover:shadow-md transition w-full sm:w-auto text-center">View Gallery</a>
                    </div>
                </div>

                <div class="hidden lg:block order-1 lg:order-2">
                    <div class="card overflow-hidden rounded-2xl transform hover:scale-105 transition-shadow duration-300">
                        <img src="/images/hero.jpg" alt="Relax" class="w-full h-64 sm:h-80 lg:h-96 object-cover">
                        <div class="p-4 sm:p-6">
                            <h3 class="text-lg sm:text-xl font-semibold">Signature Relaxation Package</h3>
                            <p class="text-xs sm:text-sm text-slate-600 mt-2">60 minutes of full-body massage using aromatherapy oils.</p>
                            <div class="mt-4 flex items-center justify-between gap-2">
                                <span class="text-base sm:text-lg font-bold text-slate-900">₱1,200.00</span>
                                <a href="{{ route('booking.create') }}" class="px-3 py-2 rounded-md bg-cyan-500 text-white font-medium text-sm hover:bg-cyan-600 transition">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="text-center mb-8 sm:mb-10">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold">Our Services</h2>
            <p class="text-slate-600 mt-2 text-sm sm:text-base">Handpicked treatments to soothe tension and replenish energy.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @foreach($services as $service)
            <article class="card p-4 sm:p-6 hover:shadow-xl transition transform hover:-translate-y-2">
                @if($service->image)
                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-full h-40 sm:h-44 object-cover rounded-xl mb-4">
                @endif
                <h3 class="text-lg sm:text-xl font-semibold mb-2">{{ $service->name }}</h3>
                <p class="text-xs sm:text-sm text-slate-600 mb-4">{{ \Illuminate\Support\Str::limit($service->description, 120) }}</p>
                <div class="flex items-center justify-between gap-2">
                    <div class="text-slate-900 font-bold text-sm sm:text-base">₱{{ number_format($service->price, 2) }}</div>
                    <a href="{{ route('booking.create') }}" class="px-3 py-2 rounded-md bg-cyan-500 text-white text-xs sm:text-sm hover:bg-cyan-600 transition">Book</a>
                </div>
            </article>
            @endforeach
        </div>
    </section>

    <!-- Gallery Preview -->
    <section class="bg-white/60 py-8 sm:py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold">Gallery</h2>
                <p class="text-slate-600 mt-2 text-sm sm:text-base">A glimpse of our calming spaces and treatments.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-4">
                @foreach($galleries as $gallery)
                <a href="{{ route('gallery') }}" class="group block relative overflow-hidden rounded-xl aspect-[4/3]">
                    <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute left-2 sm:left-4 bottom-2 sm:bottom-4 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                        <h4 class="font-semibold text-xs sm:text-sm">{{ $gallery->title }}</h4>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="text-center mt-6 sm:mt-8">
                <a href="{{ route('gallery') }}" class="cta-btn inline-block">Explore Full Gallery</a>
            </div>
        </div>
    </section>
</div>
@endsection
