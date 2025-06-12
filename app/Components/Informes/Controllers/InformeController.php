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

    public function horasTrabajadas(Request $request)
    {
        $authUser = auth()->user();
        $usuarioId = $request->input('usuario_id');
        $mes = $request->input('mes');
        $anio = $request->input('anio');


        // En el select sin administradores
        $usuarios = $authUser->isAdmin()
            ? Usuario::where('role', '!=', 'administrador')
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
            : collect();


        // Si no hay mes o año, solo cargo la vista vacía
        if (!$mes || !$anio) {
            return Inertia::render('Informes/HorasTrabajadas', [
                'usuarios' => $usuarios,
                'usuarioId' => $usuarioId,
                'semanas' => [],
                'resumen' => null,
                'resumenes' => [],
                'verTodos' => false,
            ]);
        }

        // Informe de todos en conjunto
        if ($authUser->isAdmin() && !$usuarioId) {
            $resumenes = [];

            foreach ($usuarios as $usuario) {
                $resumenes[] = [
                    'usuario_id' => $usuario->id,
                    'nombre' => $usuario->name,
                    'semanas' => $this->calcularSemanas($usuario, $mes, $anio),
                    'resumen' => $this->calcularResumen($usuario, $mes, $anio),
                ];
            }

            return Inertia::render('Informes/HorasTrabajadas', [
                'usuarios' => $usuarios,
                'usuarioId' => null,
                'resumenes' => $resumenes,
                'semanas' => [],
                'resumen' => null,
                'verTodos' => true,
            ]);
        }

        // Informe individual
        $user = $authUser->isAdmin() && $usuarioId
            ? Usuario::findOrFail($usuarioId)
            : $authUser;

        $semanas = $this->calcularSemanas($user, $mes, $anio);
        $resumen = $this->calcularResumen($user, $mes, $anio);

        return Inertia::render('Informes/HorasTrabajadas', [
            'usuarios' => $usuarios,
            'usuarioId' => $usuarioId,
            'resumenes' => [],
            'semanas' => $semanas,
            'resumen' => $resumen,
            'verTodos' => false,
        ]);
    }

    private function calcularSemanas($user, $mes, $anio)
    {
        $inicioMes = Carbon::createFromDate($anio, $mes)->startOfMonth();
        $finMes = $inicioMes->copy()->endOfMonth();

        $contrato = $user->contratos()
            ->where('fecha_inicio', '<=', $finMes)
            ->where(function ($q) use ($inicioMes) {
                $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $inicioMes);
            })->orderByDesc('fecha_inicio')->first();

        $jornadaSemanal = $contrato->horas ?? 40;

        $fichajes = Fichaje::where('usuario_id', $user->id)
            ->whereBetween('timestamp', [$inicioMes, $finMes])
            ->orderBy('timestamp')
            ->get();

        $dias = $fichajes->groupBy(fn($f) => Carbon::parse($f->timestamp)->format('Y-m-d'));

        $semanas = [];

        foreach ($dias as $fecha => $fichajesDia) {
            $semana = Carbon::parse($fecha)->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
            $semanas[$semana] = $semanas[$semana] ?? 0;

            $ordenados = $fichajesDia->sortBy('timestamp')->values();
            $entrada = null;

            foreach ($ordenados as $fichaje) {
                if ($fichaje->tipo === 'entrada') $entrada = $fichaje;
                elseif ($fichaje->tipo === 'salida' && $entrada) {
                    $start = Carbon::parse($entrada->timestamp);
                    $end = Carbon::parse($fichaje->timestamp);
                    if ($end->gt($start)) {
                        $semanas[$semana] += ($end->timestamp - $start->timestamp) / 3600;
                    }
                    $entrada = null;
                }
            }
        }

        $detalle = [];
        foreach ($semanas as $inicio => $horas) {
            $minutos = round($horas * 60);
            $detalle[$inicio] = [
                'horas' => number_format($horas, 2),
                'minutos_legibles' => floor($minutos / 60) . 'h ' . ($minutos % 60) . 'min',
            ];
        }

        return $detalle;
    }

    private function calcularResumen($user, $mes, $anio)
    {
        $detalle = $this->calcularSemanas($user, $mes, $anio);
        $total = array_sum(array_column($detalle, 'horas'));
        $contrato = $user->contratos()->latest('fecha_inicio')->first();
        $jornadaSemanal = $contrato->horas ?? 40;
        $esperadas = $jornadaSemanal * count($detalle);
        $diferencia = $total - $esperadas;

        $totalMin = round($total * 60);
        $diffMin = round($diferencia * 60);

        return [
            'trabajadas' => number_format($total, 2),
            'trabajadas_legibles' => floor($totalMin / 60) . 'h ' . ($totalMin % 60) . 'min',
            'esperadas' => $esperadas,
            'diferencia' => number_format($diferencia, 2),
            'diferencia_legibles' => sprintf('%s%dh %dmin',
                ($diferencia < 0 ? '-' : ''),
                abs(intval($diffMin / 60)),
                abs($diffMin % 60)
            )
        ];
    }

    public function registroFichajes(Request $request)
    {
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
