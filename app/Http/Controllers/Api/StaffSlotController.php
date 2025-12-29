<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffSlotController extends Controller
{
    public function mySlots(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
        ]);

        $user = $request->user();

        $slots = $user->assignedSlots()
            ->with('service:id,name')
            ->whereDate('date', $request->date)
            ->orderBy('start_time')
            ->get();

        return response()->json(['data' => $slots]);
    }
}
