<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Home Page (Optional: Redirect to Dashboard)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard Route
Route::get('/dashboard', [MainController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Protected Routes (Require Authentication)
Route::middleware('auth')->group(function () {

    // Profile Routes (Breeze Default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Locations, Employees, Tools (Admin Only)
    Route::middleware('admin')->group(function () {
        Route::resource('locations', LocationController::class);
        Route::resource('employees', EmployeeController::class);
        Route::resource('tools', ToolController::class);
    });

    // ✅ Tool Checkout
    Route::post('/checkout/{tool}', [MainController::class, 'checkout'])->name('checkout');

    // ✅ Activity Log
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity.log');
});

Route::post('/locations', function (Request $request) {
    $request->validate([
        'name' => 'required|string|unique:locations,name',
    ]);

    $location = \App\Models\Location::create([
        'name' => $request->name
    ]);

    return response()->json(['success' => true, 'location' => $location]);
});
// Include Breeze Auth Routes
require __DIR__.'/auth.php';
