<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Admin System',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Staff User
        User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@mail.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        // Facilities
        $facilities = [
            'Projector', 'Whiteboard', 'AC', 'Coffee Machine', 'High Speed WiFi', 'Sound System'
        ];

        foreach ($facilities as $f) {
            \App\Models\Facility::create(['name' => $f]);
        }

        // Rooms
        $rooms = [
            [
                'name' => 'Ruang Meeting Alpha',
                'capacity' => 10,
                'location' => 'Lantai 1',
                'description' => 'Ruang rapat formal dengan kapasitas medium.',
                'facilities' => [1, 2, 3, 5]
            ],
            [
                'name' => 'Aula Serbaguna',
                'capacity' => 100,
                'location' => 'Lantai 3',
                'description' => 'Ruangan luas untuk acara besar atau seminar.',
                'facilities' => [1, 3, 5, 6]
            ],
            [
                'name' => 'Creative Hub',
                'capacity' => 5,
                'location' => 'Lantai 2',
                'description' => 'Ruang santai untuk brainstorming tim kecil.',
                'facilities' => [3, 4, 5]
            ]
        ];

        foreach ($rooms as $r) {
            $room = \App\Models\Room::create([
                'name' => $r['name'],
                'capacity' => $r['capacity'],
                'location' => $r['location'],
                'description' => $r['description'],
            ]);
            $room->facilities()->sync($r['facilities']);
        }

        $this->call(StaffBookingSeeder::class);
    }
}
