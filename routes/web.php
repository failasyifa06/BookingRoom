<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\BookingApprovalController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Staff\RoomListController;
use App\Http\Controllers\Staff\BookingController;
use App\Http\Controllers\CalendarController;

Route::middleware('auth')->group(function () {
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('rooms', RoomController::class);
        Route::resource('facilities', FacilityController::class);
        Route::resource('users', AdminUserController::class);
        Route::get('bookings/approval', [BookingApprovalController::class, 'index'])->name('bookings.approval');
        Route::get('bookings/all', [BookingApprovalController::class, 'allBookings'])->name('bookings.all');
        Route::get('bookings/export', [BookingApprovalController::class, 'export'])->name('bookings.export');
        Route::get('bookings/{booking}', [BookingApprovalController::class, 'show'])->name('bookings.show');
        Route::post('bookings/{booking}/approve', [BookingApprovalController::class, 'approve'])->name('bookings.approve');
        Route::post('bookings/{booking}/reject', [BookingApprovalController::class, 'reject'])->name('bookings.reject');
    });

    // Staff Routes
    Route::middleware('role:staff')->group(function () {
        Route::get('rooms', [RoomListController::class, 'index'])->name('staff.rooms.index');
        Route::get('rooms/{room}', [RoomListController::class, 'show'])->name('staff.rooms.show');
        
        Route::get('bookings', [BookingController::class, 'index'])->name('staff.bookings.index');
        Route::get('bookings/create', [BookingController::class, 'create'])->name('staff.bookings.create');
        Route::post('bookings', [BookingController::class, 'store'])->name('staff.bookings.store');
        Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('staff.bookings.cancel');
    });
});

require __DIR__.'/auth.php';
