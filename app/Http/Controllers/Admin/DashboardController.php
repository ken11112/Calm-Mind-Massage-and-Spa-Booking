<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        // bookings use `booking_datetime` (datetime) in the model/migration; filter by its date portion
        $todayBookings = Booking::whereDate('booking_datetime', Carbon::today())->count();
        // count only active services
        $activeServices = Service::where('is_active', 1)->count();
        $recentBookings = Booking::with('service')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'todayBookings',
            'activeServices',
            'recentBookings'
        ));
    }
}
