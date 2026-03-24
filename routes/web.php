<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\LugarController;
use App\Http\Controllers\Admin\EventoController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\ReservaController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Rutas del panel admin - Hoteles
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    Route::get('/empresas',                      [EmpresaController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/{empresa}/edit',       [EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::put('/empresas/{empresa}',            [EmpresaController::class, 'update'])->name('empresas.update');
    Route::patch('/empresas/{empresa}/aprobar',  [EmpresaController::class, 'aprobar'])->name('empresas.aprobar');
    Route::patch('/empresas/{empresa}/rechazar', [EmpresaController::class, 'rechazar'])->name('empresas.rechazar');
    Route::delete('/empresas/{empresa}',         [EmpresaController::class, 'destroy'])->name('empresas.destroy');

    Route::get('/reservas',               [ReservaController::class, 'index'])->name('reservas.index');
    Route::post('/reservas',              [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/{reserva}/edit',[ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{reserva}',     [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/reservas/{reserva}',  [ReservaController::class, 'destroy'])->name('reservas.destroy');

});
