<?php

namespace App\Components\Fichaje\Controllers;

use App\Components\Auth\Models\Usuario;
use App\Components\Fichaje\Requests\ContratoRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Fichaje\Models\Contrato;
use Inertia\Inertia;

class ContratoController extends Controller
{
    public function index()
    {
        //return Contrato::all();

        $contratos = Contrato::with('usuario')->orderBy('fecha_inicio', 'desc')->get();
        $usuarios = Usuario::select('id', 'name')->orderBy('name')->get();

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
