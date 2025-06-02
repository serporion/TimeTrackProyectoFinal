<?php

namespace App\Components\Informes\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\Informes\Models\Auditoria;

class AuditoriaController extends Controller
{
    public function index()
    {
        return Auditoria::all();
    }

    public function show($id)
    {
        return Auditoria::findOrFail($id);
    }

    public function store(Request $request)
    {
        return Auditoria::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $auditoria = Auditoria::findOrFail($id);
        $auditoria->update($request->all());
        return $auditoria;
    }

    public function destroy($id)
    {
        return Auditoria::destroy($id);
    }
}
