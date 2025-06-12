<?php

use App\Components\Auth\Models\Credencial;
use App\Components\Auth\Models\Usuario;
use App\Components\Fichaje\Controllers\FichajeController;
use App\Components\Media\Controllers\FotoController;
use App\Components\Media\Controllers\QrEmpleadoController;
use App\Components\Informes\Controllers\InformeController;
use App\Components\Fichaje\Controllers\ContratoController;

use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RedirectIfNoConsent;
use Carbon\Carbon;
use Illuminate\Foundation\Application;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Components\Informes\Controllers\ExportacionController;
use Tymon\JWTAuth\Facades\JWTAuth;


/*
Route::get('/', function () {
    return Inertia::render('landing');
});
*/

/*
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
*/

/*
Route::get('/', function () {
    return Inertia::render('Landing');
})->name('landing');
*/


Route::get('/', function () {
    return auth()->check()
        ? redirect('/dashboard')
        : Inertia::render('Landing');
})->name('landing');


Route::get('/contacto', function () {
    return Inertia::render('Contacto');
})->name('contacto');



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', RedirectIfNoConsent::class])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/consentimiento', function () {
        return view('consentimiento');
    })->name('consentimiento');

    Route::post('/consentimiento', function (Request $request) {
        $request->validate([
            'consiente_datos' => 'required|accepted'
        ]);

        $user = Auth::user();
        $user->consiente_datos = true;
        $user->save();

        //return redirect('/dashboard')->with('success', 'Gracias por aceptar la política de privacidad.');
        return Inertia::render('Auth/ForgotPassword', [
            'status' => 'Gracias por aceptar la política. Puedes cambiar tu contraseña si lo deseas.',
        ]);
    });
});

Route::middleware(['auth'])->get('/fichar', function () {
    return Inertia::render('Fichar');
});

Route::middleware(['auth'])->get('/informes', function () {
    return Inertia::render('Informes');
});

//Route::middleware('auth:api')->get('/empleado/proximo-fichaje', [QrEmpleadoController::class, 'datosFichaje'])->name('api.qr.datos-fichaje');
//Route::middleware('auth')->get('/empleado/proximo-fichaje', [QrEmpleadoController::class, 'datosFichaje'])->name('qr.datos-fichaje');
Route::middleware('auth')->get('/empleado/proximo-fichaje', [QrEmpleadoController::class, 'mostrarQR'])->name('qr.datos-fichaje');

//Route::middleware('auth:api')->get('/empleado/fichaje-confirmado/{qr_id}', [FichajeController::class, 'verificarFichaje'])->name('api.qr.verificar-fichaje');
Route::middleware('auth')->get('/empleado/fichaje-confirmado/{qr_id}', [FichajeController::class, 'verificarFichaje'])->name('qr.verificar-fichaje');

//Route::middleware('auth')->get('/empleado/qr', [QrEmpleadoController::class, 'mostrarQR'])->name('qr.imagen');

Route::middleware('auth')->post('/fichaje/completo', [FichajeController::class, 'registrarCompleto'])->name('fichaje.completo');
Route::post('/fichaje/completo-test', [FichajeController::class, 'registrarCompleto']);



Route::middleware('auth')->get('/lector', function () {
    return Inertia::render('ProcesoFichaje');
});

Route::middleware('auth')->prefix('informes')->group(function () {
    Route::get('/horas', [InformeController::class, 'horasTrabajadas'])->name('informes.horas');
    Route::get('/fichajes', [InformeController::class, 'registroFichajes'])->name('informes.fichajes');
    Route::get('/contratos', [ContratoController::class, 'index'])->name('informes.contratos');
    Route::get('/exportar', [ContratoController::class, 'index'])->name('informes.exportar');
});

Route::get('/informes/fichajes', [InformeController::class, 'registroFichajes'])
    ->name('registro.fichajes');

Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar']);


Route::get('/informes/exportar', [ExportacionController::class, 'index'])->name('exportar.index');
Route::post('/informes/exportar', [ExportacionController::class, 'generar'])->name('exportar.generar');
Route::get('/informes/exportar/{archivo}', [ExportacionController::class, 'descargar'])->name('exportar.descargar');

//Route::post('/foto', [FotoController::class, 'store'])->name('foto.store');
Route::middleware(['auth'])->post('/foto', [FotoController::class, 'store'])->name('foto.store');


Route::get('/cert', function () {
    dd(openssl_get_cert_locations());
});

Route::get('/seed-manual', function () {
    DB::table('usuarios')->insert([
        ['id' => 1, 'name' => 'Jose Pinero', 'dni' => '000000001', 'email' => 'jose@test.com', 'password' => bcrypt('josetest'), 'role' => 'empleado', 'consiente_datos' => 0],
        ['id' => 2, 'name' => 'Paqui', 'dni' => '000000002', 'email' => 'paqui@test.com', 'password' => bcrypt('paquitest'), 'role' => 'empleado', 'consiente_datos' => 0],
        ['id' => 3, 'name' => 'Pablo', 'dni' => '000000003', 'email' => 'pablo@test.com', 'password' => bcrypt('pablotest'), 'role' => 'empleado', 'consiente_datos' => 0],
        ['id' => 4, 'name' => 'Admin Terminal', 'dni' => '000000004', 'email' => 'terminal@test.com', 'password' => bcrypt('terminal'), 'role' => 'administrador', 'consiente_datos' => 1],
        ['id' => 5, 'name' => 'Admin Completo', 'dni' => '000000005', 'email' => 'completo@test.com', 'password' => bcrypt('completo'), 'role' => 'administrador', 'consiente_datos' => 1],
        ['id' => 6, 'name' => 'Alicia', 'dni' => '000000006', 'email' => 'alicia@test.com', 'password' => bcrypt('aliciatest'), 'role' => 'empleado', 'consiente_datos' => 0],
    ]);

    $usuarios = Usuario::whereIn('id', [1, 2, 3, 4, 5, 6])->get();

    foreach ($usuarios as $usuario) {
        $token = JWTAuth::fromUser($usuario);
        Credencial::updateOrCreate(
            ['usuario_id' => $usuario->id],
            ['clave' => $token]
        );
    }

    // Contratos
    DB::table('contratos')->insert([
        ['usuario_id' => 1, 'horas' => 20, 'fecha_inicio' => '2024-12-03 18:30:37', 'fecha_fin' => '2025-05-02 18:30:37'],
        ['usuario_id' => 1, 'horas' => 40, 'fecha_inicio' => Carbon::now()],
        ['usuario_id' => 2, 'horas' => 20, 'fecha_inicio' => Carbon::now()],
        ['usuario_id' => 3, 'horas' => 20, 'fecha_inicio' => Carbon::now()],
        ['usuario_id' => 6, 'horas' => 40, 'fecha_inicio' => Carbon::now()],
    ]);

    // Permisos
    DB::table('permisos')->insert([
        ['usuario_id' => 4, 'permiso' => 'gestionar_inicio'],
        ['usuario_id' => 5, 'permiso' => 'gestionar_usuarios'],
        ['usuario_id' => 5, 'permiso' => 'gestionar_fichajes'],
        ['usuario_id' => 5, 'permiso' => 'gestionar_permisos'],
        ['usuario_id' => 5, 'permiso' => 'gestionar_inicio'],
    ]);

    return 'Usuarios y credenciales creados correctamente.';
});

Route::post('/fichaje/validar', [FichajeController::class, 'validarQr'])->name('fichaje.validar');

Route::get('/ayuda-fichaje', function () {
    return Inertia::render('AyudaFichaje');
})->name('ayuda.fichaje');

Route::get('/caracteristicas', [ContactoController::class, 'caracteristicas'])->name('caracteristicas');

Route::fallback(function () {
    return inertia('Errors/404')->withViewData(['status' => 404]);
});

require __DIR__.'/auth.php';
