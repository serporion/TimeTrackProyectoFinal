<?php

namespace App\Http\Controllers;

use App\Components\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactoController extends Controller
{
    public function index(Request $request)
    {
        /*
        return Inertia::render('Contacto', [
            'departamento' => $request->input('departamento', 'soporte'),
            'mensajePredeterminado' => $request->input(
                'mensajePredeterminado',
                'Estoy teniendo problemas con mi fichaje. Pónganse en contacto conmigo.'
            )
        ]);
        */

        return Inertia::render('Contacto', [
            'departamento' => auth()->check() ? $request->input('departamento', 'soporte') : null,
            'mensajePredeterminado' => auth()->check() ? $request->input('mensajePredeterminado', 'Estoy teniendo problemas con mi fichaje. Pónganse en contacto conmigo.') : ''
        ]);

    }
    public function enviar(Request $request)
    {
        //dd(openssl_get_cert_locations());

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'departamento' => 'required|in:comercial,tecnico,soporte,otros',
            'mensaje' => 'required|string|min:10|max:2000',
            'telefono' => 'nullable|string|max:20',
        ]);

        if (!empty($data['telefono'])) {
            $data['mensaje'] .= "\n\nTeléfono de contacto: " . $data['telefono'];
        }

        Mail::to('oscardelgadoh@gmail.com')->send(new ContactMail($data));

        return back()->with('success', 'Mensaje enviado correctamente');

    }
}
