<?php

namespace App\Components\Media\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQRRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /*
    public function rules()
    {
        return [
            'contenido' => 'required|string',
            'usuario_id' => 'required|exists:users,id',
            'estado' => 'required|in:valido,expirado',
        ];
    }
    */

    public function rules(): array
    {
        return [
            'contenido' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'in:valido,expirado'],
            'usuario_id' => 'required|exists:users,id',       // ??Funciona en producción
            'timestamp' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'contenido.required' => 'El contenido del QR es obligatorio.',
            'estado.in' => 'El estado debe ser "valido" o "expirado".',
            'timestamp.date' => 'El campo debe ser una fecha y hora válida.',
        ];
    }
}
