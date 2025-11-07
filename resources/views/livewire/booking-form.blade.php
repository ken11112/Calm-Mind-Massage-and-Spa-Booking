<section class="card max-w-3xl mx-auto">
    <header class="flex items-center space-x-4 mb-6">
        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v4h6v-4c0-1.657-1.343-3-3-3z"/></svg>
        </div>
        <div>
            <h2 class="text-2xl font-semibold">Book Your Appointment</h2>
            <p class="text-sm text-gray-500">Choose a service and pick a convenient date & time.</p>
        </div>
    </header>

    <form wire:submit.prevent="saveBooking">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Service Selection -->
            <div>
                <label for="service" class="block text-sm font-medium text-gray-700 mb-2">Select Service</label>
                <select id="service" wire:model.live="selectedService" class="w-full rounded-lg border border-gray-200 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="">Choose a service...</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} — ₱{{ number_format($service->price, 2) }}</option>
                    @endforeach
                </select>
                @error('selectedService') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Price Display -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                <div class="text-3xl font-extrabold text-gradient bg-clip-text text-transparent" style="background-image:linear-gradient(90deg,#2563eb,#1e40af)">
                    ₱{{ $price ? number_format($price, 2) : '0.00' }}
                </div>
            </div>

            <!-- Client Name -->
            <div>
                <label for="clientName" class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                <input type="text" id="clientName" wire:model="clientName" class="w-full rounded-lg border border-gray-200 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Enter your full name">
                @error('clientName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Contact Number -->
            <div>
                <label for="contactNumber" class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                <input type="text" id="contactNumber" wire:model="contactNumber" class="w-full rounded-lg border border-gray-200 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Enter your contact number">
                @error('contactNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Booking Date -->
            <div>
                <label for="bookingDate" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" id="bookingDate" wire:model="bookingDate" class="w-full rounded-lg border border-gray-200 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
                @error('bookingDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Booking Time -->
            <div>
                <label for="bookingTime" class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                <input type="time" id="bookingTime" wire:model="bookingTime" class="w-full rounded-lg border border-gray-200 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
                @error('bookingTime') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Notes -->
            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea id="notes" wire:model="notes" rows="3" class="w-full rounded-lg border border-gray-200 shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Any special requests or notes..."></textarea>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full cta-btn">
                Confirm Booking
            </button>
        </div>
    </form>
</section>

<script>
    // Listen for Livewire-emitted events (works across Livewire versions)
    if (window.Livewire && typeof window.Livewire.on === 'function') {
        Livewire.on('booking-thankyou', function(payload) {
            var message = (payload && payload.message) ? payload.message : 'Thank you for booking!';
            if (typeof showToast === 'function') {
                showToast(message);
            } else {
                alert(message);
            }
        });

        Livewire.on('open-messenger', function(payload) {
            if (payload && payload.url) {
                window.open(payload.url, '_blank');
            }
        });

        Livewire.on('show-mme-link', function(payload) {
            if (payload && payload.url) {
                var ok = confirm('Would you like to open Messenger to confirm your booking? Click OK to open Messenger.');
                if (ok) {
                    window.open(payload.url, '_blank');
                }
            }
        });
    }

    // Fallback: also handle browser events (if any other code dispatches them)
    window.addEventListener('booking-thankyou', function(e) {
        var message = (e.detail && e.detail.message) ? e.detail.message : 'Thank you for booking!';
        if (typeof showToast === 'function') {
            showToast(message);
        } else {
            alert(message);
        }
    });

    window.addEventListener('open-messenger', function(e) {
        if (e.detail && e.detail.url) {
            window.open(e.detail.url, '_blank');
        }
    });

    window.addEventListener('show-mme-link', function(e) {
        if (e.detail && e.detail.url) {
            var ok = confirm('Would you like to open Messenger to confirm your booking? Click OK to open Messenger.');
            if (ok) {
                window.open(e.detail.url, '_blank');
            }
        }
    });
</script>
