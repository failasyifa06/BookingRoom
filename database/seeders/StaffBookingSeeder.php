<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffBookingSeeder extends Seeder
{
    private const STAFF_COUNT = 5;
    private const BOOKINGS_PER_STAFF = 10;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomIds = Room::pluck('id');

        if ($roomIds->isEmpty()) {
            $roomIds = collect([
                Room::create([
                    'name' => 'Ruang Meeting Utama',
                    'capacity' => 12,
                    'location' => 'Lantai 1',
                    'description' => 'Ruang meeting default untuk data seeder.',
                ])->id,
            ]);
        }

        for ($i = 1; $i <= self::STAFF_COUNT; $i++) {
            User::updateOrCreate(
                ['email' => "staff{$i}@mail.com"],
                [
                    'name' => "Staff {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'staff',
                ]
            );
        }

        User::where('role', 'staff')->get()->each(function (User $staff) use ($roomIds) {
            $remainingBookings = self::BOOKINGS_PER_STAFF - $staff->bookings()->count();

            if ($remainingBookings <= 0) {
                return;
            }

            Booking::factory()
                ->count($remainingBookings)
                ->state(fn () => [
                    'user_id' => $staff->id,
                    'room_id' => $roomIds->random(),
                ])
                ->create();
        });
    }
}
