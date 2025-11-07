<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Calm Mind Massage') }} @hasSection('title') - @yield('title') @endif</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="theme-color" content="#2563eb" />

    <!-- Tailwind CDN for immediate styling (dev fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: '#2563eb' },
                    animation: {
                        'fadeIn': 'fadeIn 0.45s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(8px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --muted: #94a3b8;
            --bg-light: #f8fafc;
            --card-bg: #ffffff;
        }

        html,body { height:100%; }
        body { font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background: linear-gradient(180deg,var(--bg-light), #ffffff); }

        /* Loading Screen */
        #loading-screen {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.25s ease-in-out;
        }
        #loading-screen.hidden { opacity: 0; pointer-events: none; }

        .loader { width: 64px; height: 64px; border: 4px solid rgba(37,99,235,0.12); border-radius: 50%; position: relative; }
    .loader:after { content: ''; position: absolute; inset: -4px; border-radius: 50%; border: 4px solid transparent; border-top-color: var(--primary); animation: loader-spin 1s linear infinite; }
        @keyframes loader-spin { to { transform: rotate(360deg); } }

    /* Enhanced loader visuals */
    .loader-ring { border: 6px solid rgba(37,99,235,0.08); position: relative; }
    .loader-ring:before { content: ''; position: absolute; inset: 4px; border-radius: 9999px; border: 4px solid rgba(99,102,241,0.12); }
    .loader-logo { transform: translateZ(0); }

    .loader-dot { animation: loader-bounce 1s infinite; }
    .loader-dot.delay-75 { animation-delay: .08s; }
    .loader-dot.delay-150 { animation-delay: .16s; }
    .loader-dot.delay-225 { animation-delay: .24s; }
    @keyframes loader-bounce { 0%, 100% { transform: translateY(0); opacity: .6 } 50% { transform: translateY(-6px); opacity: 1 } }

        .text-gradient { background-image: linear-gradient(90deg,var(--primary),var(--primary-dark)); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .card { background: var(--card-bg); border-radius: 12px; box-shadow: 0 10px 25px rgba(16,24,40,0.06); }
        .cta-btn { display:inline-block; padding: .6rem 1.6rem; border-radius:9999px; font-weight:700; background-image:linear-gradient(90deg,var(--primary),var(--primary-dark)); color:#fff; box-shadow:0 6px 18px rgba(37,99,235,0.12); }
        nav a { transition: color .15s ease; }

        /* utility-ish small animations */
        .animate-fadeIn { animation: fadeIn 0.45s ease-in-out both; }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-b from-slate-50 to-white">
    <!-- Loading Screen (Enhanced) -->
    <div id="loading-screen" class="flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <div class="relative w-28 h-28 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 opacity-20 blur-2xl"></div>
                <div class="loader-ring w-28 h-28 rounded-full flex items-center justify-center">
                    <div class="loader-logo w-14 h-14 rounded-full bg-white/90 flex items-center justify-center shadow-lg">
                        <i class="fas fa-spa text-cyan-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <div class="text-gray-700 font-semibold text-lg">Calm Mind Massage</div>
                    <div class="flex items-center justify-center mt-2 space-x-2">
                        <span class="loader-dot w-2 h-2 rounded-full bg-cyan-500 delay-75"></span>
                        <span class="loader-dot w-2 h-2 rounded-full bg-cyan-500 delay-150"></span>
                        <span class="loader-dot w-2 h-2 rounded-full bg-cyan-500 delay-225"></span>
                    </div>
            </div>
        </div>
    </div>
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between py-4">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800">Calm Mind Massage</a>

                <nav class="space-x-6 text-gray-700">
                    <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
                    <a href="{{ route('gallery') }}" class="hover:text-gray-900">Gallery</a>
                    <a href="{{ route('booking.create') }}" class="hover:text-gray-900">Book Now</a>
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-gray-900">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-gray-900">Login</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 py-8">
        @if(session('success'))
            <div class="mb-6">
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6">
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-6 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="font-semibold mb-3">Contact Us</h4>
                    <p class="text-sm text-gray-300">Phone: 09631064414</p>
                    <p class="text-sm"><a href="https://www.facebook.com/gravyAA22" class="text-gray-300 hover:text-white">Facebook: /gravyAA22</a></p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Quick Links</h4>
                    <ul class="text-sm text-gray-300 space-y-1">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('booking.create') }}">Book Now</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Follow Us</h4>
                    <div class="flex items-center space-x-4">
                        <a href="https://www.facebook.com/gravyAA22" class="text-2xl text-gray-300 hover:text-white"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
            </div>
            <div class="mt-8 text-center text-gray-500 text-sm">© {{ date('Y') }} Calm Mind Massage. All rights reserved.</div>
        </div>
    </footer>
    @livewireScripts

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingScreen = document.getElementById('loading-screen');

            // Hide initial loader after a brief moment
            setTimeout(() => loadingScreen.classList.add('hidden'), 400);

            // Livewire hooks
            document.addEventListener('livewire:loading', () => loadingScreen.classList.remove('hidden'));
            document.addEventListener('livewire:load', () => loadingScreen.classList.add('hidden'));

            // Show loader on form submit (full page navigation too)
            document.addEventListener('submit', () => loadingScreen.classList.remove('hidden'));

            // Add to plain link clicks for visibility (skip anchor links with hashes)
            document.querySelectorAll('a[href]').forEach(a => {
                const href = a.getAttribute('href');
                if (href && !href.startsWith('#') && !href.startsWith('javascript:') && !a.hasAttribute('target')) {
                    a.addEventListener('click', () => loadingScreen.classList.remove('hidden'));
                }
            });
        });
    </script>
</body>
</html>
