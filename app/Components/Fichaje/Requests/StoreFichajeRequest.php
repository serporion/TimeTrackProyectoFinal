<?php

namespace App\Components\Fichaje\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFichajeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'usuario_id' => 'required|exists:users,id',
            'tipo' => 'required|in:entrada,salida',
            'fecha_hora' => 'required|date',
            'qr_id' => 'nullable|exists:qrs,id',
            'foto_id' => 'nullable|exists:fotos,id',
        ];
    }
}
