<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MeetingRoom;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = MeetingRoom::all();
        return view('customer.rooms', compact('rooms'));
    }
}
