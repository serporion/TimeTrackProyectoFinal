<?php

namespace App\Components\Media\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Buscamos la credencial del usuario
        $credencial = DB::table('credenciales')->where('usuario_id', $usuario->id)->value('clave');

        if (!$credencial) {
            return response()->json(['error' => 'Credencial no encontrada'], 403);
        }

        // Creamos el QR en base de datos
        $qr = QR::create([
            'contenido' => '', // Temporal, lo actualizo más abajo en este método.
            'estado' => 'valido',
            'timestamp' => now(),
        ]);

        // Creamos la cadena base para firmar
        $baseString = "{$usuario->id}|{$qr->id}|{$tipo}";

        // Generamos la firma con la credencial como clave
        $firma = hash_hmac('sha256', $baseString, $credencial);


        // Generamos el JSON con datos seguros
        $contenidoQR = json_encode([
            'usuario_id' => $usuario->id,
            'tipo' => $tipo,
            'qr_id' => $qr->id,
            'firma' => $firma,
        ]);

        // Actualizamos la columna contenido con la firma tras usar la credencial en base de datos.
        $qr->update(['contenido' => $contenidoQR]);

        // Generamos el código QR como imagen SVG
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
