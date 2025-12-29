<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\AdminReservationController;
use App\Http\Controllers\Api\AdminSlotController;
use App\Http\Controllers\Api\AdminSlotStaffController;
use App\Http\Controllers\Api\StaffSlotController;


Route::get('/ping', fn() => response()->json(['ok' => true]));

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{service}/slots', [ServiceController::class, 'slots']);

/*
|--------------------------------------------------------------------------
| Authenticated (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // profile / session
    Route::get('/me', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // user reservation
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/my/reservations', [ReservationController::class, 'myReservations']);

    /*
    |--------------------------------------------------------------------------
    | Staff (staff OR admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('staff_or_admin')->group(function () {
        Route::get('/staff/my-slots', function (Request $request) {
            $request->validate(['date' => ['required', 'date']]);

            $user = $request->user();

            $slots = $user->assignedSlots()
                ->with('service:id,name')
                ->whereDate('date', $request->date)
                ->orderBy('start_time')
                ->get();

            return response()->json(['data' => $slots]);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Admin (admin only)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('admin')->group(function () {

        // reservations moderation
        Route::get('/reservations', [AdminReservationController::class, 'index']);
        Route::post('/reservations/{reservation}/approve', [AdminReservationController::class, 'approve']);
        Route::post('/reservations/{reservation}/reject',  [AdminReservationController::class, 'reject']);

        // slot open/close
        Route::post('/slots/{slot}/close', [AdminSlotController::class, 'close']);
        Route::post('/slots/{slot}/open',  [AdminSlotController::class, 'open']);

        // staff assignment
        Route::post('/slots/{slot}/assign-staff', [AdminSlotStaffController::class, 'assign']);
        Route::get('/slots/{slot}/staff',         [AdminSlotStaffController::class, 'show']);
    });
});
