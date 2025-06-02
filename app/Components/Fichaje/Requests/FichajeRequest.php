<?php

namespace App\Components\Fichaje\Requests;

use App\Components\Fichaje\Models\Fichaje;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class FichajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'usuario_id' => ['required', 'exists:usuarios,id'],
            'qr_id' => ['required', 'exists:qrs,id'],
            'foto_id' => ['required', 'exists:fotos,id'],
            'tipo' => ['required', 'in:entrada,salida'],
            'timestamp' => ['required', 'date'],
        ];
    }

    // Movido al controlador. No deseo bloqueos del fichaje
    /*
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $usuarioId = $this->input('usuario_id');
            $tipo = $this->input('tipo');
            $timestamp = $this->input('timestamp');

            if (!$usuarioId || !$tipo || !$timestamp) {
                return; // evita fallos si faltan datos
            }

            $fecha = Carbon::parse($timestamp)->toDateString();

            // Evitar fichaje duplicado por tipo en el mismo día
            $yaFichado = Fichaje::where('usuario_id', $usuarioId)
                ->where('tipo', $tipo)
                ->whereDate('timestamp', $fecha)
                ->exists();

            if ($yaFichado) {
                $validator->errors()->add('tipo', "Ya existe una ficha de tipo {$tipo} para este día.");
            }

            // Validar que no haya salida sin entrada previa, pero comprobando si hay entrada sin salida el día anterior, de no más de 10h.
            if ($tipo === 'salida') {
                // Buscar última entrada sin salida posterior
                $entradaSinSalida = Fichaje::where('usuario_id', $usuarioId)
                    ->where('tipo', 'entrada')
                    ->whereNotExists(function ($query) use ($usuarioId) {
                        $query->select('*')
                            ->from('fichajes as salidas')
                            ->whereColumn('salidas.usuario_id', 'fichajes.usuario_id')
                            ->where('salidas.tipo', 'salida')
                            ->whereColumn('salidas.timestamp', '>', 'fichajes.timestamp');
                    })
                    ->orderBy('timestamp', 'desc')
                    ->first();

                if (!$entradaSinSalida) {
                    $validator->errors()->add('tipo', 'No se puede registrar una salida porque no hay una entrada pendiente.');
                } else {
                    // Comprobar si han pasado más de 10 horas
                    $entradaHora = \Carbon\Carbon::parse($entradaSinSalida->timestamp);
                    $salidaHora = \Carbon\Carbon::parse($this->input('timestamp'));

                    if ($entradaHora->diffInHours($salidaHora) > 10) {
                        $validator->errors()->add('timestamp', 'No se puede registrar una salida con más de 10 horas de diferencia respecto a la última entrada.');
                    }
                }
            }
        });
    }
    */

    public function messages(): array
    {
        return [
            'usuario_id.required' => 'El usuario es obligatorio.',
            'usuario_id.exists' => 'El usuario no existe.',
            'qr_id.required' => 'El QR es obligatorio.',
            'qr_id.exists' => 'El QR no existe.',
            'foto_id.required' => 'La foto es obligatoria.',
            'foto_id.exists' => 'La foto no existe.',
            'tipo.in' => 'El tipo debe ser entrada o salida.',
            'timestamp.date' => 'La fecha debe ser válida.',
        ];
    }
}
