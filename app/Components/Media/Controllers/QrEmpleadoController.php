<?php

namespace App\Components\Media\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Components\Media\Models\QR;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Components\Fichaje\Models\Fichaje;
use Carbon\Carbon;


class QrEmpleadoController extends Controller
{
    public function mostrarQR()
    {
        $usuario = Auth::user(); // debe ser rol empleado

        if (!$usuario) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $tipo = $this->obtenerTipoFichajeSiguiente($usuario->id);

        //$tipo = request('tipo', 'entrada'); // o 'salida'

        // Creamos el QR en base de datos
        $qr = QR::create([
            'contenido' => '', // temporal, lo actualizamos luego
            'estado' => 'valido',
            'timestamp' => now(),
        ]);


        // Generamos el JSON con datos seguros
        $contenidoQR = json_encode([
            'usuario_id' => $usuario->id,
            'tipo' => $tipo,
            'qr_id' => $qr->id,
        ]);

        // Actualizamos la columna contenido del QR en base de datos
        $qr->update(['contenido' => $contenidoQR]);

        // Generamos el código QR como imagen PNG
        //$image = QrCode::format('png')->size(300)->generate($contenidoQR);
        $svg = (string) QrCode::format('svg')->size(300)->generate($contenidoQR);

        /*
        return Response::make($image, 200, [
            'Content-Type' => 'image/png'
        ]);
        */

        return response()->json([
            'svg' => $svg,
            'qr_id' => $qr->id,
            'usuario_id' => $usuario->id,
            'tipo' => $tipo,
            'fecha_legible' => ucfirst(now()->translatedFormat('l d \d\e F \d\e Y \a \l\a\s H:i')),
        ]);


    }

    private function obtenerTipoFichajeSiguiente(int $usuarioId): string
    {
        $ultimo = Fichaje::where('usuario_id', $usuarioId)
            ->orderByDesc('timestamp')
            ->first();

        if (!$ultimo) return 'entrada';

        $hoy = now()->format('Y-m-d');
        $diaUltimo = Carbon::parse($ultimo->timestamp)->format('Y-m-d');

        if ($diaUltimo !== $hoy) {
            return 'entrada';
        }

        return $ultimo->tipo === 'entrada' ? 'salida' : 'entrada';
    }

}
