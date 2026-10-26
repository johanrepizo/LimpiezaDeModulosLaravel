<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VoceroController;
use App\Http\Controllers\LandingController;

// ===== AUTENTICACIÓN =====
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/cambiar-password', [AuthController::class, 'showCambiarPasswordForm'])->middleware('auth')->name('cambiar-password');
Route::post('/cambiar-password', [AuthController::class, 'cambiarPassword'])->middleware('auth');
Route::post('/recuperar-password', [AuthController::class, 'recuperarPassword']);

// ===== ADMIN =====
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'showDashboard'])->name('admin.dashboard');

    // Programas y Fichas (Estructura jerárquica)
    Route::get('/programas', [AdminController::class, 'showProgramas'])->name('admin.programas');
    Route::post('/programa/guardar', [AdminController::class, 'guardarPrograma']);
    Route::post('/programa/eliminar', [AdminController::class, 'eliminarPrograma']);
    
    Route::get('/programa/{programa}/fichas', [AdminController::class, 'showFichasPorPrograma'])->name('admin.fichas-por-programa');
    Route::post('/ficha/guardar', [AdminController::class, 'guardarFicha']);
    Route::post('/ficha/eliminar', [AdminController::class, 'eliminarFicha']);
    Route::get('/fichas', [AdminController::class, 'showFichas']); // Vista general

    // Módulos & Asignaciones
    Route::post('/modulo/guardar', [AdminController::class, 'guardarModulo']);
    Route::get('/modulos', [AdminController::class, 'showModulos']);
    Route::post('/asignacion/crear', [AdminController::class, 'crearAsignacion']);
    Route::post('/asignacion/editar', [AdminController::class, 'editarAsignacion']);
    Route::post('/asignacion/cancelar', [AdminController::class, 'cancelarAsignacion']);

    // Aprendices y Voceros (Unificados)
    Route::get('/aprendices', [AdminController::class, 'showAprendices'])->name('admin.aprendices');
    Route::post('/aprendiz/cambiar-rol', [AdminController::class, 'cambiarRolAprendiz'])->name('admin.aprendiz.cambiar-rol');
    Route::post('/vocero/activar', [AdminController::class, 'activarVocero']);
    Route::post('/vocero/desactivar', [AdminController::class, 'desactivarVocero']);
    Route::post('/vocero/reactivar', [AdminController::class, 'reactivarVocero']);
    Route::post('/vocero/reenviar-credenciales', [AdminController::class, 'reenviarCredenciales']);
    Route::get('/voceros', [AdminController::class, 'showVoceros'])->name('admin.voceros');

    // Grupos
    Route::get('/grupos', [AdminController::class, 'showGrupos']);

    // Evidencias
    Route::get('/evidencias', [AdminController::class, 'showEvidencias']);
    Route::get('/evidencia/turno', [AdminController::class, 'getEvidenciaTurno']);

    // Reportes de Cumplimiento Trimestrales
    Route::get('/reportes', [AdminController::class, 'showReportes'])->name('admin.reportes');
    Route::get('/reportes/exportar/csv', [AdminController::class, 'exportarReporteCsv'])->name('admin.reportes.csv');

    // Notificaciones
    Route::get('/notificaciones', [AdminController::class, 'showNotificaciones']);

    // Sincronización
    Route::get('/sincronizacion', [AdminController::class, 'showSincronizacion']);
});

// ===== VOCERO =====
Route::middleware(['auth', 'vocero'])->prefix('vocero')->group(function () {
    Route::get('/', [VoceroController::class, 'showDashboard'])->name('vocero.dashboard');

    // Grupos
    Route::post('/grupo/guardar', [VoceroController::class, 'guardarGrupo']);
    Route::post('/grupo/editar', [VoceroController::class, 'editarGrupo']);
    Route::post('/grupo/eliminar', [VoceroController::class, 'eliminarGrupo']);
    Route::get('/grupos', [VoceroController::class, 'showGrupos']);
    Route::get('/integrantes/ids', [VoceroController::class, 'getIntegrantesIds']);

    // Evidencias
    Route::post('/evidencia/subir', [VoceroController::class, 'subirEvidenciaTurno']);
    Route::get('/evidencias', [VoceroController::class, 'showEvidencias']);
    Route::get('/subir-evidencia', [VoceroController::class, 'showSubirEvidencia']);
    Route::get('/evidencia/turno', [VoceroController::class, 'getEvidenciaTurno']);

    // Aprendices
    Route::get('/aprendices', [VoceroController::class, 'showAprendices']);

    // Notificaciones
    Route::get('/notificaciones', [VoceroController::class, 'showNotificaciones']);
});

// ===== INICIO / LANDING =====
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/inicio', [LandingController::class, 'index'])->name('inicio');
Route::fallback(function () {
    return redirect('/login');
});
