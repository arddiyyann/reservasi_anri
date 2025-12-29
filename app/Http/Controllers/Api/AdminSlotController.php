<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceSlot;

class AdminSlotController extends Controller
{
    public function close(ServiceSlot $slot)
    {
        $slot->update(['is_closed' => true]);

        return response()->json([
            'message' => 'Slot ditutup.',
            'data' => $slot
        ]);
    }

    public function open(ServiceSlot $slot)
    {
        $slot->update(['is_closed' => false]);

        return response()->json([
            'message' => 'Slot dibuka.',
            'data' => $slot
        ]);
    }
}
