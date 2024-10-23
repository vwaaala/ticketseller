<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;

Auth::routes(['register' => false]);

Route::get('/', [GuestController::class, 'index'])->name('home');
Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
Route::post('/webhook/xendit', [OrderController::class, 'webhook'])->name('webhook.xendit');

// admin routes
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'dashborad'])->name('dashborad');
    // Route to display the authenticated user's profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Route to display the profile edit form
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Route to update the user's profile
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // master data
    Route::resource('tickets', TicketController::class)->except(['show']);

    // orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});
