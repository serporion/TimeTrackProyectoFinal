<?php

namespace App\Components\Informes\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExportacionController extends Controller
{

    public function index()
    {
        $archivos = collect(Storage::disk('public')->files('exportaciones'))
            ->filter(fn($f) => str_ends_with($f, '.json'))
            ->map(fn($f) => basename($f));

        return inertia('Informes/Exportar', [
            'archivos' => $archivos,
        ]);
    }

    /*
    public function index()
    {
        $path = storage_path('app/public/exportaciones');

        $archivos = collect(File::files($path))
            ->filter(fn($f) => $f->getExtension() === 'json')
            ->map(fn($f) => $f->getFilename());

        return inertia('Informes/Exportar', [
            'archivos' => $archivos
        ]);
    }
    */

    public function generar(Request $request)
    {
        $anio = $request->input('anio', now()->year);
        $mes = $request->input('mes', now()->month);

        $usuarios = DB::table('usuarios')
            ->select('id', 'name', 'dni')
            ->get();

        $fichajes = DB::table('fichajes')
            ->whereYear('timestamp', $anio)
            ->whereMonth('timestamp', $mes)
            ->orderBy('timestamp')
            ->get();

        $estructura = [
            'año' => (int) $anio,
            'empleados' => [],
        ];

        foreach ($usuarios as $usuario) {
            $usuario_fichajes = $fichajes->where('usuario_id', $usuario->id);

            $dias = [];

            foreach ($usuario_fichajes->groupBy(fn($f) => Carbon::parse($f->timestamp)->toDateString()) as $fecha => $registros) {
                $turnos = [];

                $entradas = $registros->where('tipo', 'entrada')->values();
                $salidas = $registros->where('tipo', 'salida')->values();

                $max = max($entradas->count(), $salidas->count());

                for ($i = 0; $i < $max; $i++) {
                    $entrada = optional($entradas->get($i))->timestamp;
                    $salida = optional($salidas->get($i))->timestamp;

                    $turnos[] = [
                        'entrada' => $entrada ? Carbon::parse($entrada)->format('H:i') : null,
                        'salida' => $salida ? Carbon::parse($salida)->format('H:i') : null,
                    ];
                }

                $dias[] = [
                    'fecha' => $fecha,
                    'horas' => $turnos,
                ];
            }

            $estructura['empleados'][] = [
                'nombre' => $usuario->name,
                'dni' => $usuario->dni,
                'dias' => $dias,
            ];
        }

        $filename = "registro_horario_{$anio}_" . str_pad($mes, 2, '0', STR_PAD_LEFT) . ".json";
        //Storage::put("public/exportaciones/$filename", json_encode($estructura, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        Storage::disk('public')->put("exportaciones/$filename", json_encode($estructura, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->route('exportar.index')->with('success', "Archivo $filename generado correctamente.");
    }

    public function descargar($archivo)
    {
        $path = storage_path("app/public/exportaciones/{$archivo}");

        if (!file_exists($path)) {
            abort(404);
        }

        return Response::download($path);
    }
}
