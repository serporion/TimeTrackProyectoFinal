<?php

namespace App\Components\Fichaje\Controllers;

use App\Components\Fichaje\Models\Fichaje;
use App\Components\Fichaje\Requests\FichajeRequest;
use App\Components\Media\Models\Foto;
use App\Components\Media\Models\QR;
use App\Components\Media\Requests\FotoRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Components\Fichaje\Requests\StoreFichajeRequest;
use App\Components\Fichaje\Services\FichajeService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class FichajeController extends Controller
{
    protected $fichajeService;

    public function __construct(FichajeService $fichajeService)
    {
        $this->fichajeService = $fichajeService;
    }

    public function store(StoreFichajeRequest $request)
    {
        $fichaje = $this->fichajeService->registrarFichaje($request->validated());

        return response()->json([
            'message' => 'Fichaje registrado correctamente',
            'data' => $fichaje
        ]);
    }

    //public function registrarCompleto(FichajeRequest $request)
    public function registrarCompleto(Request $request)
    {

        $advertencia = null;




        /*
        $request->validate([
            //'qr_data' => 'required|json',
            'qr_data' => 'required',
        ]);
        */

        //dd($request);
        $qrData = json_decode($request->input('qr_data'), true);

        /*
        $clave = $request->input('clave'); //tokenPX

        //Validación de credencial del usuario
        $credencial = DB::table('credenciales')
            ->where('usuario_id', $qrData['usuario_id'])
            ->first();

        Log::debug('Validando credencial', [
            'usuario_id' => $qrData['usuario_id'],
            'clave_recibida' => $clave,
            'credencial_encontrada' => $credencial?->clave,
        ]);


        if (!$credencial || !hash_equals($credencial->clave, $clave)) {
            return response()->json([
                'error' => 'Token de autenticación inválido o faltante.'
            ], 403);
        }

        */

        // Validación de firma HMAC con credencial
        $credencial = DB::table('credenciales')
            ->where('usuario_id', $qrData['usuario_id'])
            ->value('clave');

        if (!$credencial) {
            return response()->json([
                'error' => 'Credencial no encontrada.'
            ], 403);
        }

        $baseString = "{$qrData['usuario_id']}|{$qrData['qr_id']}|{$qrData['tipo']}";
        $firmaEsperada = hash_hmac('sha256', $baseString, $credencial);

        // Comparar la firma recibida vs la esperada
        if (!isset($qrData['firma']) || !hash_equals($firmaEsperada, $qrData['firma'])) {
            return response()->json([
                'error' => 'QR manipulado o firma inválida.'
            ], 403);
        }


        $tipoEsperado = $this->fichajeService->tipoEsperadoFichaje($qrData['usuario_id']);
        $tipoQr = $qrData['tipo'];

        /*
        $request->merge([
            'usuario_id' => $qrData['usuario_id'] ?? null,
            'qr_id' => $qrData['qr_id'] ?? null,
            'foto_id' => $qrData['foto_id'] ?? null,
            'tipo' => $qrData['tipo'] ?? null,
            'timestamp' => now()->toDateTimeString(),
        ]);
        */

        //$foto = $this->guardarFoto($request->file('imagen')); //PruebaFichaje. Descomentar esta línea y el if de Abajo quitarlo
        /*
        if (!$request->hasFile('imagen')) {
            // Cargar imagen falsa desde /public
            $ruta = 'fotos/placeholder.png';

            $foto = Foto::create([
                'ruta_imagen' => $ruta,
                'timestamp' => now(),
            ]);
        } else {
            // flujo real
            $ruta = $request->file('imagen')->store('fotos', 'public');

            $foto = Foto::create([
                'ruta_imagen' => $ruta,
                'timestamp' => now(),
            ]);
        }
        */

        $qr = QR::findOrFail($qrData['qr_id']);

        if (!$qr) {
            return response()->json(['error' => 'QR no encontrado']);
        }


        if ($qr->estado === 'expirado') {
            return response()->json(['error' => 'expirado']);
        }
        if ($qr->estado === 'confirmado') {
            return response()->json(['error' => 'QR ya usado']);
        }

        if ($qr->timestamp->diffInSeconds(now()) > 15) {
            $qr->update(['estado' => 'expirado']);
            return response()->json(['estado' => 'expirado']);
        }

        $fotoId = $qrData['foto_id'] ?? null; //nuevo

        $fichaje = $this->fichajeService->registrarFichajeCompleto([
            'usuario_id' => $qrData['usuario_id'],
            'tipo' => $qrData['tipo'],
            'qr_id' => $qrData['qr_id'],
            'foto_id' => $fotoId, //nuevo
            //'foto_id' => $fotoId->id
            'timestamp' => now(),
        ]);

        if ($tipoEsperado !== $tipoQr) {
            $advertencia = "️ El sistema esperaba un fichaje de tipo '$tipoEsperado', pero el QR indica '$tipoQr'. El registro se ha guardado igualmente. Consulte al departamento de RRHH";

            Log::warning('Discrepancia en tipo de fichaje detectada', [
                'fichaje_id' => $fichaje->id,
                'usuario_id' => $fichaje->usuario_id,
                'tipo_qr' => $qrData['tipo'],
                'tipo_esperado' => $tipoEsperado,
                'timestamp' => $fichaje->timestamp,
                'mensaje' => $advertencia
            ]);

        }

        $fecha = Carbon::parse($fichaje->timestamp)->toDateString();

        $entradaSinSalida = Fichaje::where('usuario_id', $fichaje->usuario_id)
            ->where('tipo', 'entrada')
            ->where('timestamp', '<', $fichaje->timestamp)
            ->whereNotExists(function ($query) {
                $query->select('*')
                    ->from('fichajes as salidas')
                    ->whereColumn('salidas.usuario_id', 'fichajes.usuario_id')
                    ->where('salidas.tipo', 'salida')
                    ->whereColumn('salidas.timestamp', '>', 'fichajes.timestamp');
            })
            ->orderBy('timestamp', 'desc')
            ->first();

        $yaFichado = Fichaje::where('usuario_id', $fichaje->usuario_id)
                ->where('tipo', $fichaje->tipo)
                ->whereDate('timestamp', $fecha)
                ->count() > 1;

        /*

        if ($yaFichado && $fichaje->tipo === 'salida') {
            $advertencia = ' Ya has registrado una salida hoy. Verifica si es correcto o contacta con RRHH.';
        }
        */


        if ($yaFichado && $fichaje->tipo === 'salida') {
            $advertencia = '️ Ya has registrado una salida hoy. Verifica si es correcto o contacta con RRHH.';

            Log::warning('Advertencia de fichaje duplicado', [
                'fichaje_id' => $fichaje->id,
                'usuario_id' => $fichaje->usuario_id,
                'tipo' => $fichaje->tipo,
                'timestamp' => $fichaje->timestamp,
                'mensaje' => $advertencia
            ]);

        }

        if ($fichaje->tipo === 'salida' && $entradaSinSalida) {
            $entradaHora = Carbon::parse($entradaSinSalida->timestamp);
            $salidaHora = Carbon::parse($fichaje->timestamp);

            if ($entradaHora->diffInHours($salidaHora) > 10) {
                $advertencia = ' Se ha detectado un posible error: hay más de 10 horas entre la entrada y esta salida. Por favor, comunícate con RRHH para regularizar tu jornada.';
            }

            Log::warning('Fichaje con posible error de jornada prolongada', [
                'fichaje_id' => $fichaje->id,
                'usuario_id' => $fichaje->usuario_id,
                'entrada_anterior' => $entradaHora->toDateTimeString(),
                'salida_actual' => $salidaHora->toDateTimeString(),
                'diferencia_horas' => $entradaHora->diffInHours($salidaHora),
                'mensaje' => $advertencia
            ]);

        }

        $usuario = DB::table('usuarios')->where('id', $fichaje->usuario_id)->first();


        return response()->json([
            'estado' => 'confirmado',
            'message' => 'Fichaje registrado correctamente',
            'fichaje' => $fichaje,
            'nombre' => $usuario?->name ?? null,
            'advertencia' => $advertencia,
        ]);
    }


    public function verificarFichaje($qrId)
    {
        /*
        $fichaje = Fichaje::where('qr_id', $qrId)->first();

        if (!$fichaje) {
            return response()->json(['confirmado' => false]);
        }

        return response()->json([
            'confirmado' => true,
            'tipo' => $fichaje->tipo,
            'hora' => Carbon::parse($fichaje->timestamp)->translatedFormat('l d \d\e F \a \l\a\s H:i'),
        ]);
        */

        $qr = QR::find($qrId);

        if (!$qr) {
            return response()->json(['estado' => 'no_existe']);
        }

        $expirado = $qr->timestamp->diffInSeconds(now()) > 15;

        Log::info('Expirado?', [
            'qr_id' => $qrId,
            'expirado' => $expirado,
            'timestamp_qr' => $qr->timestamp->toDateTimeString(),
            'ahora' => now()->toDateTimeString(),
            'diff' => $qr->timestamp->diffInSeconds(now()),
        ]);


        if ($expirado) {
            $qr->update(['estado' => 'expirado']);
            return response()->json(['estado' => 'expirado']);
        }

        if ($qr->estado === 'confirmado') {
            return response()->json(['estado' => 'ya_usado']);
        }

        $fichaje = Fichaje::where('qr_id', $qrId)->first();

        if (!$fichaje) {
            return response()->json(['estado' => 'esperando']);
        }

        $qr->update(['estado' => 'confirmado']);

        return response()->json([
            'estado' => 'confirmado',
            'tipo' => $fichaje->tipo,
            'hora' => Carbon::parse($fichaje->timestamp)->translatedFormat('l d \d\e F \a \l\a\s H:i'),
        ]);
    }
}
