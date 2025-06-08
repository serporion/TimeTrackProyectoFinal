<?php

namespace App\Components\Informes\Controllers;

use App\Components\Auth\Models\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Informes\Models\Informe;
use Inertia\Inertia;

use App\Components\Fichaje\Models\Fichaje;
use Carbon\Carbon;

class InformeController extends Controller
{
    public function index()
    {
        return Informe::all();
    }

    public function show($id)
    {
        return Informe::findOrFail($id);
    }

    public function store(Request $request)
    {
        return Informe::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $informe = Informe::findOrFail($id);
        $informe->update($request->all());
        return $informe;
    }
    public function destroy($id)
    {
        return Informe::destroy($id);
    }

    /*
    public function horasTrabajadas(Request $request)
    {
        $userId = auth()->id();
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        $fichajes = Fichaje::where('usuario_id', $userId)
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->get();

        $horasTotales = 0;

        foreach ($fichajes->groupBy(function ($f) {
            return $f->created_at->format('Y-m-d');
        }) as $dia => $fichajesDia) {
            $entrada = $fichajesDia->where('tipo', 'entrada')->first();
            $salida = $fichajesDia->where('tipo', 'salida')->first();

            if ($entrada && $salida) {
                $horasTotales += $salida->created_at->diffInMinutes($entrada->created_at) / 60;
            }
        }

        return response()->json(['horas' => round($horasTotales, 2)]);
    }
    */

    public function horasTrabajadas(Request $request)
    {
        //$user = auth()->user();
        $authUser = auth()->user();
        $usuarioId = $request->input('usuario_id');     //nuevoHoras

        if ($authUser->isAdmin() && $usuarioId) {   //nuevoHoras
            $user = Usuario::findOrFail($usuarioId);
        } else {
            $user = $authUser;
        }

        $mes = $request->input('mes');
        $anio = $request->input('anio');

        if (!$mes || !$anio) {
            return Inertia::render('Informes/HorasTrabajadas', [
                'semanas' => [],
                'resumen' => null,
                'usuarios' => $user->isAdmin()  //nuevoHoras
                    ? Usuario::select('id', 'name')->orderBy('name')->get()
                    : [],
                'usuarioId' => $usuarioId,
            ]);
        }

        $inicioMes = Carbon::createFromDate($anio, $mes)->startOfMonth();
        $finMes = $inicioMes->copy()->endOfMonth();

        // Obtener contrato válido
        $contrato = $user->contratos()
            ->where('fecha_inicio', '<=', $finMes)
            ->where(function ($query) use ($inicioMes) {
                $query->whereNull('fecha_fin')
                    ->orWhere('fecha_fin', '>=', $inicioMes);
            })
            ->orderByDesc('fecha_inicio')
            ->first();

        $jornadaSemanal = $contrato->horas ?? 40;

        // Fichajes del mes
        $fichajes = Fichaje::where('usuario_id', $user->id)
            ->whereBetween('timestamp', [$inicioMes, $finMes])
            ->orderBy('timestamp')
            ->get();

        // Agrupar por día
        $dias = $fichajes->groupBy(function ($f) {
            return Carbon::parse($f->timestamp)->format('Y-m-d');
        });

        // Agrupar por semana
        $semanas = [];

        foreach ($dias as $fecha => $fichajesDia) {
            $semana = Carbon::parse($fecha)->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
            if (!isset($semanas[$semana])) {
                $semanas[$semana] = 0;
            }

            $fichajesOrdenados = $fichajesDia->sortBy('timestamp')->values();
            $enEspera = null;

            foreach ($fichajesOrdenados as $fichaje) {
                if ($fichaje->tipo === 'entrada') {
                    $enEspera = $fichaje;
                } elseif ($fichaje->tipo === 'salida' && $enEspera) {
                    $entradaTime = Carbon::parse($enEspera->timestamp);
                    $salidaTime = Carbon::parse($fichaje->timestamp);

                    if ($salidaTime->gt($entradaTime)) {
                        $horas = ($salidaTime->timestamp - $entradaTime->timestamp) / 3600;
                        $semanas[$semana] += $horas;
                    }

                    $enEspera = null;
                }
            }
        }

        // Convertir semanas a formato legible
        $detalleSemanas = [];

        foreach ($semanas as $inicioSemana => $horas) {
            $minutos = (int) round($horas * 60);
            $horasEnteras = floor($minutos / 60);
            $restoMinutos = $minutos % 60;

            $detalleSemanas[$inicioSemana] = [
                'horas' => number_format($horas, 2),
                'minutos_legibles' => "{$horasEnteras}h {$restoMinutos}min"
            ];
        }

        // Resumen mensual
        $totalTrabajadas = array_sum($semanas);
        $totalEsperadas = $jornadaSemanal * count($semanas);
        $diferencia = $totalTrabajadas - $totalEsperadas;

        $trabajadasMin = (int) round($totalTrabajadas * 60);
        $diffMin = (int) round($diferencia * 60);

        $resumen = [
            'trabajadas' => number_format($totalTrabajadas, 2),
            'trabajadas_legibles' => floor($trabajadasMin / 60) . 'h ' . ($trabajadasMin % 60) . 'min',
            'esperadas' => $totalEsperadas,
            'diferencia' => number_format($diferencia, 2),
            /*
            'diferencia_legibles' => ($diferencia >= 0 ? '' : '-') .
                abs(floor($diffMin / 60)) . 'h ' . (abs($diffMin) % 60) . 'min'
            */
            'diferencia_legibles' => sprintf(
                '%s%dh %dmin',
                ($diferencia < 0 ? '-' : ''),
                abs(intval($diffMin / 60)),
                abs($diffMin % 60)
            )
        ];

        return Inertia::render('Informes/HorasTrabajadas', [
            'semanas' => $detalleSemanas,
            'resumen' => $resumen,
            'usuarios' => $authUser->isAdmin() //nuevoHoras
                ? Usuario::select('id', 'name')->orderBy('name')->get()
                : [],
            'usuarioId' => $usuarioId,
        ]);
    }


    public function registroFichajes(Request $request)
    {
        //return Fichaje::with('usuario')->get();
        /*
        $fichajes = Fichaje::with('usuario')->orderBy('timestamp', 'desc')->get();

        return Inertia::render('Informes/RegistroFichajes', [
            'fichajes' => $fichajes
        ]);
        */

        $user = auth()->user();

        $query = Fichaje::with('usuario');

        // Filtro por usuario
        if (!$user->isAdmin()) {
            $query->where('usuario_id', $user->id);
        } elseif ($request->filled('usuarioId')) {
            $query->where('usuario_id', $request->usuarioId);
        }

        // Filtro por fechas
        if ($request->filled('fechaInicio')) {
            $query->whereDate('timestamp', '>=', $request->fechaInicio);
        }

        if ($request->filled('fechaFin')) {
            $query->whereDate('timestamp', '<=', $request->fechaFin);
        }

        $fichajes = $query->orderBy('timestamp', 'desc')
            ->paginate(10)
            ->withQueryString(); // Mantiene los filtros al paginar

        $usuarios = $user->isAdmin()
            ? Usuario::select('id', 'name')->orderBy('name')->get()
            : [];

        return Inertia::render('Informes/RegistroFichajes', [
            'fichajes' => $fichajes,
            'usuarios' => $usuarios,
            'filters' => $request->only(['usuarioId', 'fechaInicio', 'fechaFin']),
        ]);

    }

}
