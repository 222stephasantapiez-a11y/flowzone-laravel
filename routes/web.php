<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\EmpresaDashboardController;
use App\Http\Controllers\Empresa\BlogEmpresaController;
use App\Http\Controllers\Empresa\GastronomiaEmpresaController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\LugarController;
use App\Http\Controllers\Admin\EventoController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\ReservaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GastronomiaController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ImagenController;

// ── Páginas públicas ─────────────────────────────────────────
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/hoteles', [PageController::class, 'hoteles'])->name('hoteles');
Route::get('/hoteles/{hotel}', [PageController::class, 'detalleHotel'])->name('hoteles.detalle');
Route::get('/lugares', [PageController::class, 'lugares'])->name('lugares');
Route::get('/lugares/{lugar}', [PageController::class, 'detalleLugar'])->name('lugares.detalle');
Route::get('/eventos', [PageController::class, 'eventos'])->name('eventos');
Route::get('/gastronomia', [PageController::class, 'gastronomia'])->name('gastronomia');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{post:slug}', [PageController::class, 'blogPost'])->name('blog.post');
Route::get('/contacto', [PageController::class, 'contacto'])->name('contacto');
Route::get('/maps', [PageController::class, 'maps'])->name('maps');
Route::get('/maps/buscar', [PageController::class, 'mapsBuscar'])->name('maps.buscar');

// ── Autenticación ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/registro', [AuthController::class, 'showRegistro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registro']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Área de usuario autenticado ──────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/reservar', [PageController::class, 'reservaForm'])->name('reservar');
    Route::post('/reservar', [PageController::class, 'reservaStore'])->name('reservar.store');
    Route::get('/mis-reservas', [PageController::class, 'misReservas'])->name('mis-reservas');
    Route::get('/favoritos', [PageController::class, 'favoritos'])->name('favoritos');
    Route::post('/favoritos/toggle', [FavoritoController::class, 'toggle'])->name('favoritos.toggle');
    Route::post('/calificaciones', [CalificacionController::class, 'store'])->name('calificaciones.store');
});

// ── Panel empresa ────────────────────────────────────────────
Route::middleware(['auth', 'es_empresa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', [EmpresaDashboardController::class, 'index'])->name('dashboard');
    Route::post('/solicitud', [EmpresaDashboardController::class, 'enviarSolicitud'])->name('solicitud');

    // Blog empresa
    Route::get('/blog', [BlogEmpresaController::class, 'index'])->name('blog.index');
    Route::post('/blog', [BlogEmpresaController::class, 'store'])->name('blog.store');
    Route::get('/blog/{post}/edit', [BlogEmpresaController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{post}', [BlogEmpresaController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [BlogEmpresaController::class, 'destroy'])->name('blog.destroy');

    // Gastronomía empresa
    Route::get('/gastronomia', [GastronomiaEmpresaController::class, 'index'])->name('gastronomia.index');
    Route::post('/gastronomia', [GastronomiaEmpresaController::class, 'store'])->name('gastronomia.store');
    Route::get('/gastronomia/{gastronomium}/edit', [GastronomiaEmpresaController::class, 'edit'])->name('gastronomia.edit');
    Route::put('/gastronomia/{gastronomium}', [GastronomiaEmpresaController::class, 'update'])->name('gastronomia.update');
    Route::delete('/gastronomia/{gastronomium}', [GastronomiaEmpresaController::class, 'destroy'])->name('gastronomia.destroy');
});

// ── Panel admin ──────────────────────────────────────────────
Route::middleware(['auth', 'es_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/hoteles', [HotelController::class, 'index'])->name('hoteles.index');
    Route::post('/hoteles', [HotelController::class, 'store'])->name('hoteles.store');
    Route::get('/hoteles/{hotel}/edit', [HotelController::class, 'edit'])->name('hoteles.edit');
    Route::put('/hoteles/{hotel}', [HotelController::class, 'update'])->name('hoteles.update');
    Route::delete('/hoteles/{hotel}', [HotelController::class, 'destroy'])->name('hoteles.destroy');
    Route::get('/hoteles/export/excel', [App\Http\Controllers\Admin\HotelController::class, 'exportExcel'])->name('hoteles.export.excel');
    Route::get('/hoteles/export/pdf', [App\Http\Controllers\Admin\HotelController::class, 'exportPdf'])->name('hoteles.export.pdf');
    Route::post('/hoteles/import/excel', [App\Http\Controllers\Admin\HotelController::class, 'importExcel'])->name('hoteles.import.excel');

    
    Route::get('/lugares', [LugarController::class, 'index'])->name('lugares.index');
    Route::post('/lugares', [LugarController::class, 'store'])->name('lugares.store');
    Route::get('/lugares/{lugar}/edit', [LugarController::class, 'edit'])->name('lugares.edit');
    Route::put('/lugares/{lugar}', [LugarController::class, 'update'])->name('lugares.update');
    Route::delete('/lugares/{lugar}', [LugarController::class, 'destroy'])->name('lugares.destroy');
    Route::get('/lugares/export/excel', [App\Http\Controllers\Admin\LugarController::class, 'exportExcel'])->name('lugares.export.excel');
    Route::get('/lugares/export/pdf', [App\Http\Controllers\Admin\LugarController::class, 'exportPdf'])->name('lugares.export.pdf');
    Route::post('/lugares/import/excel', [App\Http\Controllers\Admin\LugarController::class, 'importExcel'])->name('lugares.import.excel');

    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');
    Route::get('/eventos/export/excel',[App\Http\Controllers\Admin\EventoController::class, 'exportExcel'])->name('eventos.export.excel');
    Route::get('eventos/export/pdf', [App\Http\Controllers\Admin\EventoController::class, 'exportPdf'])->name('eventos.export.pdf');
    Route::post('/eventos/import/excel', [App\Http\Controllers\Admin\EventoController::class, 'importExcel'])->name('eventos.import.excel');

    Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/{empresa}/edit', [EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::put('/empresas/{empresa}', [EmpresaController::class, 'update'])->name('empresas.update');
    Route::patch('/empresas/{empresa}/aprobar', [EmpresaController::class, 'aprobar'])->name('empresas.aprobar');
    Route::patch('/empresas/{empresa}/rechazar', [EmpresaController::class, 'rechazar'])->name('empresas.rechazar');
    Route::delete('/empresas/{empresa}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');
    Route::patch('/notificaciones/{notificacion}/leer', [EmpresaController::class, 'marcarLeida'])->name('notificaciones.leer');
    Route::post('/notificaciones/leer-todas', [EmpresaController::class, 'marcarTodasLeidas'])->name('notificaciones.leer-todas');

    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/{reserva}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{reserva}', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    Route::get('/reservas/export/excel', [ReservaController::class, 'exportExcel'])->name('reservas.export.excel');
    Route::post('/reservas/import/excel', [ReservaController::class, 'importExcel'])->name('reservas.import.excel');
    Route::get('/reservas/export/pdf', [ReservaController::class, 'exportPdf'])->name('reservas.export.pdf');
    // Gastronomía admin
    Route::get('/gastronomia', [GastronomiaController::class, 'index'])->name('gastronomia.index');
    Route::post('/gastronomia', [GastronomiaController::class, 'store'])->name('gastronomia.store');
    Route::get('/gastronomia/{gastronomium}/edit', [GastronomiaController::class, 'edit'])->name('gastronomia.edit');
    Route::put('/gastronomia/{gastronomium}', [GastronomiaController::class, 'update'])->name('gastronomia.update');
    Route::delete('/gastronomia/{gastronomium}', [GastronomiaController::class, 'destroy'])->name('gastronomia.destroy');
    Route::get('/gastronomia/export/excel', [App\Http\Controllers\Admin\GastronomiaController::class, 'exportExcel'])->name('gastronomia.export.excel');
    Route::get('/gastronomia/export/pdf', [App\Http\Controllers\Admin\GastronomiaController::class, 'exportPdf'])->name('gastronomia.export.pdf');
    Route::post('/gastronomia/import/excel', [App\Http\Controllers\Admin\GastronomiaController::class, 'importExcel'])->name('gastronomia.import.excel');

    // Blog admin
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{blog}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{blog}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{blog}', [BlogController::class, 'destroy'])->name('blog.destroy');
    Route::patch('/blog/{blog}/publicar', [BlogController::class, 'togglePublicado'])->name('blog.publicar');

    // Gestión de imágenes hero/galería
    Route::get('/imagenes', [ImagenController::class, 'index'])->name('imagenes.index');
    Route::post('/imagenes', [ImagenController::class, 'store'])->name('imagenes.store');
    Route::patch('/imagenes/{imagen}/toggle', [ImagenController::class, 'toggleActiva'])->name('imagenes.toggle');
    Route::post('/imagenes/orden', [ImagenController::class, 'orden'])->name('imagenes.orden');
    Route::delete('/imagenes/{imagen}', [ImagenController::class, 'destroy'])->name('imagenes.destroy');

    // Gestión de usuarios
    Route::get('/usuarios', [App\Http\Controllers\Admin\UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/export/excel', [App\Http\Controllers\Admin\UsuarioController::class, 'exportExcel'])->name('usuarios.export.excel');
    Route::get('/usuarios/export/pdf', [App\Http\Controllers\Admin\UsuarioController::class, 'exportPdf'])->name('usuarios.export.pdf');
    Route::post('/usuarios/import/excel', [App\Http\Controllers\Admin\UsuarioController::class, 'importExcel'])->name('usuarios.import.excel');
});
