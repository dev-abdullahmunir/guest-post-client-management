<?php

use Illuminate\Support\Facades\Route;
use Modules\ClientManagement\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| ClientManagement Module Routes
|--------------------------------------------------------------------------
*/
Route::prefix('clients')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/{id}', [ClientController::class, 'show'])->name('clients.show');
    Route::put('/{id}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
});
