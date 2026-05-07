<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Room;

class RoomListController extends Controller
{
    public function index()
    {
        $rooms = Room::with('facilities')->latest()->paginate(12);
        return view('staff.rooms.index', compact('rooms'));
    }

    public function show(Room $room)
    {
        $room->load('facilities');
        return view('staff.rooms.show', compact('room'));
    }
}
