<?php

namespace App\Components\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdministradorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id') ?? $this->usuario_id;

        return [
            /*
            'usuario_id' => [
                'required',
                'exists:usuarios,id',
                $this->isMethod('POST')
                    ? 'unique:administradores,usuario_id'
                    : 'sometimes' // lo ignoras en update si no quieres editarlo
            ],
            */
            'usuario_id' => $this->isMethod('POST')
                ? ['required', 'exists:usuarios,id', 'unique:administradores,usuario_id']
                : ['required', 'exists:usuarios,id'],

            'permisos' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    $validos = [
                        'gestionar_usuarios',
                        'gestionar_fichajes',
                        'gestionar_permisos',
                        'gestionar_inicio',
                    ];
                    foreach ($value as $permiso) {
                        if (!in_array($permiso, $validos, true)) {
                            $fail("El permiso '$permiso' no es válido.");
                        }
                    }
                }
            ]
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'usuario_id.required' => 'Debes seleccionar un usuario.',
            'usuario_id.unique' => 'Este usuario ya es un administrador.',
            'permisos.required' => 'Debes seleccionar al menos un permiso.',
            'permisos.array' => 'El formato de los permisos es incorrecto.',
            'permisos.min' => 'Selecciona al menos un permiso.',
        ];
    }
}
