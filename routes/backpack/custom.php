<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

/*
Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('usuario', 'UsuarioCrudController');
    Route::get('usuario/{id}/regenerar-credencial', [\App\Http\Controllers\Admin\UsuarioCrudController::class, 'regenerateCredencial']);
    Route::crud('credencial', 'CredencialCrudController');
    Route::crud('administrador', 'AdministradorCrudController');
}); // this should be the absolute last line of this file
*/

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => [
        'web',
        'auth',
        \App\Http\Middleware\CheckIfAdmin::class,
    ],
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('usuario', 'UsuarioCrudController');
    Route::get('usuario/{id}/regenerar-credencial', [\App\Http\Controllers\Admin\UsuarioCrudController::class, 'regenerateCredencial']);
    Route::get('/usuario-administrador-ajax', [\App\Http\Controllers\Admin\AdministradorCrudController::class, 'buscarAdministradores']);
    Route::crud('credencial', 'CredencialCrudController');
    Route::crud('administrador', 'AdministradorCrudController');
    Route::crud('contrato', 'ContratoCrudController');
    Route::crud('informe', 'InformeCrudController');
    Route::crud('auditoria', 'AuditoriaCrudController');
    Route::crud('fichaje', 'FichajeCrudController');
}); // this should be the absolute last line of this file
/**
 * DO NOT ADD ANYTHING HERE.
 */
