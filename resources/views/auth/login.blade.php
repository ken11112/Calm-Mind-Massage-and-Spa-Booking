@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <!-- Left promotional panel -->
        <div class="lg:col-span-7 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-2xl p-10 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="max-w-lg">
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4">Welcome back</h1>
                <p class="text-lg text-white/90 mb-6">Sign in to manage bookings, view your appointments and enjoy exclusive offers. Relax — we got everything handled.</p>

                <ul class="space-y-3 mt-6">
                    <li class="flex items-start space-x-3">
                        <span class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center"> <i class="fas fa-calendar-check text-white"></i> </span>
                        <div>
                            <div class="font-semibold">Easy Booking</div>
                            <div class="text-sm text-white/80">Reserve your spot in seconds.</div>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3">
                        <span class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center"> <i class="fas fa-spa text-white"></i> </span>
                        <div>
                            <div class="font-semibold">Calming Treatments</div>
                            <div class="text-sm text-white/80">Tailored services for relaxation.</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right auth card -->
        <div class="lg:col-span-5">
            <div class="card p-8 shadow-2xl rounded-2xl">
                <div class="mb-6 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-gradient-to-tr from-cyan-500 to-blue-500 flex items-center justify-center text-white text-2xl shadow">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="mt-4 text-2xl font-semibold">Sign in to your account</h2>
                    <p class="text-sm text-slate-500">Enter your credentials to access the dashboard.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full rounded-lg border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-200" />
                        @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        <input id="password" name="password" type="password" required class="mt-1 block w-full rounded-lg border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-200" />
                        @error('password') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center text-sm">
                            <input type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-cyan-600" {{ old('remember') ? 'checked' : '' }}>
                            <span class="ml-2 text-slate-600">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-cyan-600 hover:underline">Forgot password?</a>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="w-full cta-btn flex items-center justify-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <span>Login</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center text-sm text-slate-500">
                    Don't have an account? <a href="{{ route('register') }}" class="text-cyan-600 hover:underline">Create one</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
