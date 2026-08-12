<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Operaciones\ClienteController;
use App\Http\Controllers\Operaciones\ServicioController;
use App\Http\Controllers\Operaciones\TarifaClienteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Si ya está autenticado, no debe volver a /login: eso crearía un
    // bucle de redirecciones contra el middleware 'guest'.
    if (auth()->check()) {
        return redirect()->to(
            (new LoginController)->redirectPathFor(auth()->user())
        );
    }

    return redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Panel Administrativo (Admin/Operador) — Anexo Panel Web.
Route::middleware(['auth', 'role:ADMIN,OPERADOR'])
    ->prefix('operaciones')
    ->name('operaciones.')
    ->group(function () {
        Route::get('/', function () {
            return view('operaciones.dashboard');
        })->name('dashboard');

        // Catálogos (A-02, A-03, A-06): "gestiona precios personalizados por
        // cliente" es responsabilidad del Administrador — solo ADMIN.
        Route::middleware('role:ADMIN')->group(function () {
            Route::resource('clientes', ClienteController::class)->except(['show']);
            Route::resource('servicios', ServicioController::class)->except(['show']);
            Route::resource('tarifas', TarifaClienteController::class)
                ->parameters(['tarifas' => 'tarifa'])
                ->except(['show']);
        });
    });

// App de Vendedor (ruta/tablet) — Anexo App.
Route::middleware(['auth', 'role:VENDEDOR'])
    ->prefix('vendedor')
    ->name('vendedor.')
    ->group(function () {
        Route::get('/', function () {
            return view('vendedor.home');
        })->name('home');
    });
