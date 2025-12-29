<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Service::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
        ]);
    }

    public function slots(Service $service, Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
        ]);

        $date = $request->date;

        $slots = $service->slots()
            ->whereDate('date', $date)
            ->where('is_closed', false)
            ->orderBy('start_time')
            ->get()
            ->map(function ($slot) {
                $activeCount = $slot->reservations()
                    ->whereIn('status', ['pending', 'approved', 'checked_in'])
                    ->count();

                return [
                    'id' => $slot->id,
                    'date' => $slot->date->toDateString(),
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'capacity' => $slot->capacity,
                    'available' => max(0, $slot->capacity - $activeCount),
                    'is_closed' => (bool) $slot->is_closed,
                ];
            });

        return response()->json(['data' => $slots]);
    }
}
