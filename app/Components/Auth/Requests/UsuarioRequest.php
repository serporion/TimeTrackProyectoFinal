<?php

namespace App\Components\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
        /*
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
        ];
        */

        $usuarioId = $this->route('usuario') ?? $this->id;

        $rules = [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:usuarios,email' . ($usuarioId ? ',' . $usuarioId : ''),
            ],
            'role' => ['required', 'in:empleado,administrador'],
            'dni' => [
                'required',
                'size:9',
                'unique:usuarios,dni' . ($usuarioId ? ',' . $usuarioId : ''),
            ],
        ];


        if ($this->isMethod('POST')) {
            $rules['password'] = [
                'required',
                'min:8',
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // Al menos un carácter especial
            ];
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['password'] = [
                'nullable',
                'min:8',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
            ];
        }
        return $rules;
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
    public function messages()
    {
        return [
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos un carácter especial (por ejemplo: !@#$%^&*).',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.size' => 'El DNI debe tener exactamente 9 caracteres.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'role.in' => 'El rol debe ser empleado o administrador.',
        ];
    }
}
