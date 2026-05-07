<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('08:00', '16:00');
        $endTime = clone $startTime;
        $endTime->modify('+' . rand(1, 4) . ' hours');

        return [
            'room_id' => \App\Models\Room::inRandomOrder()->first()?->id ?? 1,
            'user_id' => \App\Models\User::where('role', 'staff')->inRandomOrder()->first()?->id ?? 1,
            'date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'purpose' => $this->faker->sentence(rand(4, 10)),
            'status' => 'pending',
            'approval_notes' => null,
            'approved_by' => null,
        ];
    }
}
