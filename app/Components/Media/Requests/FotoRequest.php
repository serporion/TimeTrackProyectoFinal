<?php

namespace App\Components\Media\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FotoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'imagen' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'imagen.required' => 'Debes subir una imagen.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'Solo se permiten imágenes jpeg, png o jpg.',
            'imagen.max' => 'La imagen no debe superar los 2MB.',
        ];
    }
}
