<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\Booking;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class BookingForm extends Component
{
    public $services;
    public $selectedService;
    public $clientName;
    public $contactNumber;
    public $bookingDate;
    public $bookingTime;
    public $price;
    public $notes;

    public function mount()
    {
        $this->services = Service::where('is_active', true)->get();
    }

    public function updatedSelectedService($value)
    {
        if ($value) {
            $service = Service::find($value);
            $this->price = $service->price;
        } else {
            $this->price = null;
        }
    }

    public function saveBooking()
    {
        $this->validate([
            'selectedService' => 'required|exists:services,id',
            'clientName' => 'required|min:3',
            'contactNumber' => 'required|regex:/^[0-9\+\-\(\)\/\s]*$/',
            'bookingDate' => 'required|date|after_or_equal:today',
            'bookingTime' => 'required',
        ]);

        $booking = Booking::create([
            'service_id' => $this->selectedService,
            'client_name' => $this->clientName,
            'contact_number' => $this->contactNumber,
            'booking_datetime' => $this->bookingDate . ' ' . $this->bookingTime,
            'price' => $this->price,
            'notes' => $this->notes,
            'status' => 'pending'
        ]);

        // Send Facebook message
        try {
            $pageAccessToken = config('services.facebook.page_access_token');
            $pageId = config('services.facebook.page_id');
            $adminPsid = config('services.facebook.admin_psid');
            $pageUsername = config('services.facebook.page_username');

            $message = "New Booking Received!\n\n"
                . "Client: {$this->clientName}\n"
                . "Service: {$booking->service->name}\n"
                . "Date/Time: {$booking->booking_datetime}\n"
                . "Contact: {$this->contactNumber}\n"
                . "Notes: {$this->notes}";

            // Preferred: send directly to admin PSID via Send API (requires admin_psid)
            if ($pageAccessToken && $adminPsid) {
                $response = Http::withToken($pageAccessToken)->post('https://graph.facebook.com/v15.0/me/messages', [
                    'recipient' => ['id' => $adminPsid],
                    'message' => ['text' => $message]
                ]);

                if ($response->failed()) {
                    \Log::warning('Facebook Send API failed', ['response' => $response->body()]);
                }
            }

            // Provide a client-facing m.me link so the client can click it and generate a referral (PSID)
            if ($pageUsername) {
                $messengerUrl = 'https://m.me/' . $pageUsername . '?ref=' . urlencode('booking_' . $booking->id);
                // Dispatch an event so frontend can show the m.me link (user must click to open Messenger and create referral)
                $this->dispatch('show-mme-link', ['url' => $messengerUrl, 'booking_id' => $booking->id]);
            } else {
                // if page username not available via env, try DB settings
                $settings = \App\Models\FacebookSetting::first();
                if ($settings && $settings->page_username) {
                    $messengerUrl = 'https://m.me/' . $settings->page_username . '?ref=' . urlencode('booking_' . $booking->id);
                    $this->dispatch('show-mme-link', ['url' => $messengerUrl, 'booking_id' => $booking->id]);
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't stop the booking process
            \Log::error('Facebook message failed: ' . $e->getMessage());
        }

        // Friendly thank-you message
        session()->flash('success', 'Thank you for booking! We have received your request.');

        // Dispatch browser event so the UI can show a nicer toast/modal and optionally open messenger (if provided earlier)
        // Dispatch an event to notify the frontend
        $this->dispatch('booking-thankyou', ['message' => 'Thank you for booking! We sent a notification to our team.']);

        $this->reset(['selectedService', 'clientName', 'contactNumber', 'bookingDate', 'bookingTime', 'price', 'notes']);
    }

    public function render()
    {
        return view('livewire.booking-form');
    }
}
