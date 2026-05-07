<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateBookingStatus extends Command
{
    protected $signature = 'bookings:update-status';
    protected $description = 'Update booking status to completed after end time has passed';

    public function handle()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->toTimeString();

        $bookings = Booking::where('status', 'approved')
            ->where(function($query) use ($today, $currentTime) {
                $query->where('date', '<', $today)
                      ->orWhere(function($q) use ($today, $currentTime) {
                          $q->where('date', $today)
                            ->where('end_time', '<', $currentTime);
                      });
            })
            ->update(['status' => 'completed']);

        $this->info("Successfully updated {$bookings} bookings to completed.");
    }
}
