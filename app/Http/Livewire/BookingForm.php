<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Service;
use App\Models\Booking;

class BookingForm extends Component
{
    public $services = [];
    public $selectedService = null;
    public $price = 0;
    public $clientName;
    public $contactNumber;
    public $bookingDate;
    public $bookingTime;
    public $notes;

    public function mount()
    {
        $this->services = Service::where('is_active', true)->orderBy('name')->get();
    }

    public function updatedSelectedService($value)
    {
        $service = Service::find($value);
        $this->price = $service ? $service->price : 0;
    }

    public function saveBooking()
    {
        $this->validate([
            'selectedService' => 'required|exists:services,id',
            'clientName' => 'required|string|max:255',
            'contactNumber' => 'required|string|max:50',
            'bookingDate' => 'required|date',
            'bookingTime' => 'required',
            'price' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $datetime = $this->bookingDate . ' ' . $this->bookingTime;

        Booking::create([
            'service_id' => $this->selectedService,
            'client_name' => $this->clientName,
            'contact_number' => $this->contactNumber,
            'booking_datetime' => $datetime,
            'price' => $this->price,
            'notes' => $this->notes,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Your booking has been received. We will contact you to confirm.');

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.booking-form');
    }
}
