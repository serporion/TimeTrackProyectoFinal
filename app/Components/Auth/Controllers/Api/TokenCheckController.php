<?php

namespace App\Components\Auth\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenCheckController extends Controller
{
    public function check(Request $request)
    {
        $usuario = auth()->user(); // autenticado vía token JWT

        return response()->json([
            'valido' => true,
            'usuario_id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'email' => $usuario->email,
            'role' => $usuario->role,
        ]);
    }
}

