<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    /*
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),  // comparto usuario en todas las vistas
                'permisos' => fn () => $request->user()?->administrador?->getPermisosComoArray() ?? [], // comparto permisos en todas las vistas.
            ],
        ];
    }
    */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(), // comparto usuario en todas las vistas
                'permisos' => fn () => $request->user()?->administrador?->getPermisosComoArray() ?? [], // Se comparten permisos del backend al frontend
            ],
            'flash' => [ // Comunicación entre el back y el front via session
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }

}
