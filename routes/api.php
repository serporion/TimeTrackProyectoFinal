<?php

use App\Components\Auth\Controllers\Api\TokenCheckController;
use App\Components\Fichaje\Controllers\FichajeController;
use App\Components\Media\Controllers\FotoController;
use Illuminate\Support\Facades\Route;

//use App\Components\Media\Controllers\QRController;
//use App\Components\Media\Controllers\QrEmpleadoController;


Route::middleware('auth:api')->get('/check-token', [TokenCheckController::class, 'check']);
Route::middleware(['auth:api'])->post('/fichaje', [FichajeController::class, 'store']);
//Route::middleware(['auth:api'])->post('/qr', [QRController::class, 'store']);
//Route::middleware(['auth:api'])->post('/foto', [FotoController::class, 'store']);

//Route::post('/foto', [FichajeController::class, 'guardarFoto']);


//Route::post('/contacto', ContactoController::class);


//Route::post('/fichaje/completo', [FichajeController::class, 'registrarCompleto'])->middleware('auth:api');
//Route::middleware('auth:api')->get('/empleado/qr', [QrEmpleadoController::class, 'mostrarQR']);

//Route::middleware('auth:api')->get('/empleado/proximo-fichaje', [QrEmpleadoController::class, 'datosFichaje'])->name('api.qr.datos-fichaje');
//Route::middleware('auth:api')->get('/empleado/fichaje-confirmado/{qr_id}', [FichajeController::class, 'verificarFichaje'])->name('api.qr.verificar-fichaje');

//Route::middleware('auth:api')->get('/empleado/proximo-fichaje', [QrEmpleadoController::class, 'datosFichaje']);
//Route::middleware('auth:api')->get('/empleado/fichaje-confirmado/{qr_id}', [FichajeController::class, 'verificarFichaje']);





