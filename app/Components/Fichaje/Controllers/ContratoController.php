<?php

namespace App\Components\Fichaje\Controllers;

use App\Components\Auth\Models\Usuario;
use App\Components\Fichaje\Requests\ContratoRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Fichaje\Models\Contrato;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ContratoController extends Controller
{
    public function index()
    {
        //return Contrato::all();

        /*
        $contratos = Contrato::with('usuario')->orderBy('fecha_inicio', 'desc')->get();
        $usuarios = Usuario::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('Informes/Contratos', [
            'contratos' => $contratos,
            'usuarios' => $usuarios,
        ]);
        */

        $user = Auth::user();

        if ($user->role === 'administrador') {
            // Administrador puede ver todos los contratos
            $contratos = Contrato::with('usuario')->orderBy('fecha_inicio', 'desc')->get();
            $usuarios = Usuario::select('id', 'name')
                ->where('role', 'empleado')  // <-- Filtra solo empleados
                ->orderBy('name')
                ->get();
        } else {
            // Empleado solo puede ver sus propios contratos
            $contratos = Contrato::with('usuario')
                ->where('usuario_id', $user->id)
                ->orderBy('fecha_inicio', 'desc')
                ->get();
            $usuarios = []; // No mostrar el filtro de usuarios si no es admin
        }

        return Inertia::render('Informes/Contratos', [
            'contratos' => $contratos,
            'usuarios' => $usuarios,
        ]);

    }

    public function show($id)
    {
        return Contrato::findOrFail($id);
    }

    public function store(ContratoRequest $request)
    {
        //return Contrato::create($request->all());
        Contrato::create($request->validated());
    }

    public function update(ContratoRequest  $request, $id)
    {
        $contrato = Contrato::findOrFail($id);
        $contrato->update($request->all());
        return $contrato;
    }

    public function destroy($id)
    {
        return Contrato::destroy($id);
    }

}
