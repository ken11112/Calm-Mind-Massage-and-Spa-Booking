<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class BookingManager extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $date = '';
    public $perPage = 10;

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function updateStatus($bookingId, $newStatus)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->status = $newStatus;
        $booking->save();

        session()->flash('success', 'Booking status updated successfully.');
    }

    public function render()
    {
        $query = Booking::with('service')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('client_name', 'like', '%' . $this->search . '%')
                        ->orWhere('contact_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->date, function ($query) {
                $query->whereDate('booking_datetime', $this->date);
            })
            ->orderBy('booking_datetime', 'desc');

        $bookings = $query->paginate($this->perPage);

        return view('livewire.admin.booking-manager', [
            'bookings' => $bookings
        ]);
    }
}
