<?php

namespace App\Components\Fichaje\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class ContratoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check(); // o Auth::check() si es fuera de Backpack
    }

    public function rules(): array
    {
        return [
            'usuario_id' => ['required', 'exists:usuarios,id'],
            'horas' => ['required', 'numeric', 'min:0.5'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'usuario_id.required' => 'El campo usuario es obligatorio.',
            'usuario_id.exists' => 'El usuario seleccionado no es válido.',
            'horas.required' => 'Debe indicar las horas del contrato.',
            'horas.numeric' => 'El campo horas debe ser un número.',
            'horas.min' => 'Debe ser al menos 0.5 hora.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'Debe proporcionar una fecha válida.',
            'fecha_fin.date' => 'Debe proporcionar una fecha válida.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $usuarioId = $this->input('usuario_id');
            $fechaFin = $this->input('fecha_fin');
            $contratoId = $this->route('id'); //Lo pide. Null si es creación

            // Comprueba la fecha fin del contrato que debe ser posterior a la última fecha de fichaje.
            if ($usuarioId && $fechaFin) {
                $ultimoFichaje = DB::table('fichajes')
                    ->where('usuario_id', $usuarioId)
                    ->orderByDesc('timestamp')
                    ->value('timestamp');

                if ($ultimoFichaje && $fechaFin <= $ultimoFichaje) {
                    $validator->errors()->add('fecha_fin', 'La fecha de fin debe ser posterior al último fichaje del usuario (' . Carbon::parse($ultimoFichaje)->format('d/m/Y H:i') . ').');
                }
            }

            // ¿Existe ya un contrato activo (sin fecha_fin) para este usuario?
            $contratoVigente = DB::table('contratos')
                ->where('usuario_id', $usuarioId)
                ->whereNull('fecha_fin')
                ->when($contratoId, function ($query, $contratoId) {
                    // Si es actualización, excluimos el mismo contrato
                    $query->where('id', '<>', $contratoId);
                })
                ->exists();

            if ($contratoVigente) {
                $validator->errors()->add('usuario_id', 'Ya existe un contrato vigente para este usuario. Debe finalizarlo antes de crear uno nuevo.');
            }
        });
    }
}
