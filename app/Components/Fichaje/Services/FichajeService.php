<?php

namespace App\Components\Fichaje\Services;

use App\Components\Fichaje\Models\Fichaje;
use Carbon\Carbon;

class FichajeService
{
    public function registrarFichaje(array $data): Fichaje
    {
        return Fichaje::create($data);
    }

    public function registrarFichajeCompleto(array $data): Fichaje
    {
        return Fichaje::create($data);
    }

    public function tipoEsperadoFichaje(int $usuarioId): string
    {
        $ultimo = Fichaje::where('usuario_id', $usuarioId)
            ->orderByDesc('timestamp')
            ->first();

        if (!$ultimo) return 'entrada';

        // Comparar si fue el mismo día
        $hoy = now()->format('Y-m-d');
        $diaUltimo = Carbon::parse($ultimo->timestamp)->format('Y-m-d');

        if ($diaUltimo !== $hoy) {
            return 'entrada'; // nuevo día
        }

        return $ultimo->tipo === 'entrada' ? 'salida' : 'entrada';
    }

}
