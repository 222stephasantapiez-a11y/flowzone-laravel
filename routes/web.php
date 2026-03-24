<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\LugarController;
use App\Http\Controllers\Admin\EventoController;

Route::get('/', function () {
    return view('admin.dashboard');
});

// Rutas del panel admin - Hoteles
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/hoteles',              [HotelController::class, 'index'])->name('hoteles.index');
    Route::post('/hoteles',             [HotelController::class, 'store'])->name('hoteles.store');
    Route::get('/hoteles/{hotel}/edit', [HotelController::class, 'edit'])->name('hoteles.edit');
    Route::put('/hoteles/{hotel}',      [HotelController::class, 'update'])->name('hoteles.update');
    Route::delete('/hoteles/{hotel}',   [HotelController::class, 'destroy'])->name('hoteles.destroy');

    Route::get('/lugares',              [LugarController::class, 'index'])->name('lugares.index');
    Route::post('/lugares',             [LugarController::class, 'store'])->name('lugares.store');
    Route::get('/lugares/{lugar}/edit', [LugarController::class, 'edit'])->name('lugares.edit');
    Route::put('/lugares/{lugar}',      [LugarController::class, 'update'])->name('lugares.update');
    Route::delete('/lugares/{lugar}',   [LugarController::class, 'destroy'])->name('lugares.destroy');

    Route::get('/eventos',               [EventoController::class, 'index'])->name('eventos.index');
    Route::post('/eventos',              [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{evento}',      [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{evento}',   [EventoController::class, 'destroy'])->name('eventos.destroy');

});
