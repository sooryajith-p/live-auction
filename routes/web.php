<?php

use App\Http\Controllers\LiveAuctionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('products', ProductController::class);
    });

    Route::middleware(['role:bidder'])->prefix('live-auctions')->name('auctions.live.')->group(function () {
        Route::get('/', [LiveAuctionController::class, 'index'])->name('index');
        Route::get('/{slug}', [LiveAuctionController::class, 'show'])->name('show');
        Route::post('/bid', [LiveAuctionController::class, 'placeBid'])->name('bid.store');
        Route::post('/message', [LiveAuctionController::class, 'sendMessage'])->name('chat.send');
    });
});

require __DIR__ . '/auth.php';
