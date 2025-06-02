<?php

namespace App\Components\Media\Controllers;

use App\Components\Media\Requests\FotoRequest;
use App\Http\Controllers\Controller;
use App\Components\Media\Models\Foto;
use Illuminate\Support\Str;

class FotoController extends Controller
{
    public function store(FotoRequest $request)
    {

        $folder = 'fotos/' . now()->format('Y/m');

        $usuario = auth()->user();

        //$nombre = $usuario ? Str::slug($usuario->name, '-') : 'anonimo';
        $nombreQr = $request->input('nombre_empleado') ?? 'anonimo';
        $nombre = Str::slug($nombreQr, '-');

        $filename = $nombre . '-' . uniqid() . '.' . $request->file('imagen')->getClientOriginalExtension();

        $ruta = $request->file('imagen')->storeAs($folder, $filename, 'public');

        $foto = Foto::create([
            'ruta_imagen' => $ruta,
            'timestamp' => now(),
        ]);

        return response()->json([
            'message' => 'Foto guardada correctamente',
            'data' => $foto,
            'foto_id' => $foto->id,
        ]);
    }
}

