<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $bookings = Booking::with(['room', 'user'])
            ->where('status', 'approved')
            ->whereBetween('date', [
                date('Y-m-d', strtotime($start)),
                date('Y-m-d', strtotime($end))
            ])
            ->get();

        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->room->name . ' - ' . $booking->user->name,
                'start' => $booking->date . 'T' . $booking->start_time,
                'end' => $booking->date . 'T' . $booking->end_time,
                'description' => $booking->purpose,
                'room' => $booking->room->name,
                'user' => $booking->user->name,
                'color' => '#4f46e5', // Indigo-600
            ];
        });

        return response()->json($events);
    }
}
