<?php

use Illuminate\Support\Facades\Route;
use Modules\ClientManagement\Http\Controllers\ClientController;
use Modules\ClientManagement\Http\Controllers\OrderController;
use Modules\ClientManagement\Http\Controllers\OrderItemController;

/*
|--------------------------------------------------------------------------
| ClientManagement Module Routes
|--------------------------------------------------------------------------
*/

// Web Page Views
Route::get('/clients', [ClientController::class, 'renderView'])->name('clients');
Route::get('/orders', [OrderController::class, 'renderView'])->name('orders');

// Clients API
Route::prefix('api/clients')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('api.clients.index');
    Route::post('/', [ClientController::class, 'store'])->name('api.clients.store');
    Route::get('/{id}', [ClientController::class, 'show'])->name('api.clients.show');
    Route::post('/{id}/update', [ClientController::class, 'update'])->name('api.clients.update');
    Route::post('/{id}/delete', [ClientController::class, 'destroy'])->name('api.clients.destroy');
});

// Orders API
Route::prefix('api/orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('api.orders.index');
    Route::post('/', [OrderController::class, 'store'])->name('api.orders.store');
    Route::get('/{id}', [OrderController::class, 'show'])->name('api.orders.show');
    Route::post('/{id}/update', [OrderController::class, 'update'])->name('api.orders.update');
    Route::post('/{id}/delete', [OrderController::class, 'destroy'])->name('api.orders.destroy');
    
    // Order Items Sub-resource
    Route::get('/{id}/items', [OrderItemController::class, 'index'])->name('api.orders.items.index');
    Route::post('/{id}/items', [OrderItemController::class, 'store'])->name('api.orders.items.store');
});

// Order Items Direct API
Route::prefix('api/order-items')->group(function () {
    Route::get('/{id}', [OrderItemController::class, 'show'])->name('api.order-items.show');
    Route::post('/{id}/update', [OrderItemController::class, 'update'])->name('api.order-items.update');
    Route::post('/{id}/delete', [OrderItemController::class, 'destroy'])->name('api.order-items.destroy');
});
