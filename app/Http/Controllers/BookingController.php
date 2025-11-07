<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;

class BookingController extends Controller
{
    //
    /**
     * Show booking form.
     */
    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('booking.create', compact('services'));
    }

    /**
     * Store a booking (fallback if Livewire not used).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'client_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:50'],
            'booking_datetime' => ['required', 'date'],
            'price' => ['required', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        Booking::create($data + ['status' => 'pending']);

        return redirect()->route('home')->with('success', 'Your booking was created. We will contact you to confirm.');
    }
}
