<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceSlot;
use App\Models\User;
use Illuminate\Http\Request;

class AdminSlotStaffController extends Controller
{
    public function assign(Request $request, ServiceSlot $slot)
    {
        $data = $request->validate([
            'staff_user_ids' => ['required', 'array', 'min:1'],
            'staff_user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        // (opsional) validasi role harus staff/admin
        $validStaffCount = User::whereIn('id', $data['staff_user_ids'])
            ->whereIn('role', ['staff', 'admin'])
            ->count();

        if ($validStaffCount !== count($data['staff_user_ids'])) {
            return response()->json(['message' => 'Semua user harus role staff/admin.'], 422);
        }

        // set assignment (replace)
        $slot->staff()->sync($data['staff_user_ids']);

        return response()->json([
            'message' => 'Staf ditugaskan ke slot.',
            'data' => $slot->load('staff:id,name,email,role')
        ]);
    }

    public function show(ServiceSlot $slot)
    {
        return response()->json([
            'data' => $slot->load('staff:id,name,email,role')
        ]);
    }
}
