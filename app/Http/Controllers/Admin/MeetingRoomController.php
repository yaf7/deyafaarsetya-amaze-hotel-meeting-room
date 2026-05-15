<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class MeetingRoomController extends Controller
{
    public function index()
    {
        $rooms = MeetingRoom::latest()->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $layout = [
            'theater' => $request->theater,
            'classroom' => $request->classroom,
            'round_table' => $request->round_table,
            'u_shape' => $request->u_shape,
        ];

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('rooms', 'public');
        }

        MeetingRoom::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'facilities' => $request->facilities,
            'layout' => $layout,
            'price' => 0,
            'photo' => $photoPath,
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruang meeting berhasil ditambahkan.');
    }

    public function edit(MeetingRoom $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, MeetingRoom $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $layout = [
            'theater' => $request->theater,
            'classroom' => $request->classroom,
            'round_table' => $request->round_table,
            'u_shape' => $request->u_shape,
        ];

        $updateData = [
            'name' => $request->name,
            'capacity' => $request->capacity,
            'facilities' => $request->facilities,
            'layout' => $layout,
            'price' => 0,
        ];

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($room->photo && Storage::disk('public')->exists($room->photo)) {
                Storage::disk('public')->delete($room->photo);
            }
            $updateData['photo'] = $request->file('photo')->store('rooms', 'public');
        }

        $room->update($updateData);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruang meeting berhasil diperbarui.');
    }

    public function destroy(MeetingRoom $room)
    {
        // Hapus foto jika ada
        if ($room->photo && Storage::disk('public')->exists($room->photo)) {
            Storage::disk('public')->delete($room->photo);
        }
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Ruang meeting berhasil dihapus.');
    }
}
