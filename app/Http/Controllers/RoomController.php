<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('currentRental')->orderBy('room_number')->get();
        return view('rooms.index', compact('rooms'));
    }

    public function manage()
    {
        $rooms = Room::orderBy('room_number')->get();
        return view('rooms.manage', compact('rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_number' => 'required|string|max:20|unique:rooms',
            'name_en' => 'nullable|string|max:255',
            'name_am' => 'nullable|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
        ]);
        Room::create($data);
        AuditLog::log('room_created', 'Room', null, "Room {$data['room_number']}");
        return redirect()->route('rooms.manage')->with('success', __('m.success'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'room_number' => 'required|string|max:20|unique:rooms,room_number,' . $room->id,
            'name_en' => 'nullable|string|max:255',
            'name_am' => 'nullable|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
        ]);
        $room->update($data);
        return redirect()->route('rooms.manage')->with('success', __('m.success'));
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.manage')->with('success', __('m.success'));
    }
}
