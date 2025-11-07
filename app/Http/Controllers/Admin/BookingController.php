<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Redirect;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings in admin.
     */
    public function index()
    {
        return view('admin.bookings.index');
    }

    /**
     * Update booking status (e.g. pending -> confirmed).
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $status = $request->input('status') ?? $request->route('status') ?? $request->get('new_status');

        // If status not provided in request, try to read from route parameter named status
        if (!$status && $request->route('booking')) {
            // nothing - keep current status
        }

        if ($status) {
            $booking->status = $status;
            $booking->save();
            return Redirect::back()->with('success', 'Booking status updated.');
        }

        return Redirect::back()->with('error', 'No status provided.');
    }
}
