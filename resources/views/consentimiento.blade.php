@extends('layouts.plain')

@section('content')

        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h2 class="h4 mb-3 text-dark">
                        Bienvenido a la primera entrada al Sistema.
                    </h2>
                    <h3 class="h5 mb-3 text-dark">
                        Consentimiento de tratamiento de datos e imagen
                    </h3>

                    <p class="text-secondary small">
                        De acuerdo con el Reglamento General de Protección de Datos (RGPD) y la Ley Orgánica 3/2018,
                        de Protección de Datos Personales y garantía de los derechos digitales, solicitamos tu consentimiento
                        para tratar tus datos personales y tu imagen con fines exclusivamente laborales, como control de horarios y verificación de identidad.
                    </p>

                    <form method="POST" action="{{ route('consentimiento') }}">
                        @csrf

                        <div class="form-check my-3">
                            <input
                                class="form-check-input border border-secondary border-2 rounded-1"
                                type="checkbox"
                                name="consiente_datos"
                                id="consiente_datos"
                                required
                                style="width: 1.25em; height: 1.25em;"
                            >
                            <label class="form-check-label" for="consiente_datos">
                                Acepto la política de privacidad y autorizo el tratamiento de mis datos e imagen con fines internos.
                            </label>
                        </div>

                        @error('consiente_datos')
                        <div class="text-danger small mb-2">
                            ❗ {{ $message }}
                        </div>
                        @enderror

                        <div class="d-flex justify-content-between mt-4 gap-4">
                            <a href="{{ route('logout') }}"
                               class="btn btn-lg px-6 py-2.5 rounded-pill border-0 fw-bold btn-gradient"
                               style="background: linear-gradient(135deg, #d3d3d3, #a9a9a9); color: white; box-shadow: 0 4px 15px rgba(169, 169, 169, 0.4);"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Cancelar y cerrar sesión
                            </a>

                            <button type="submit"
                                    class="btn btn-lg px-6 py-2.5 rounded-pill border-0 fw-bold btn-gradient"
                                    style="background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);">
                                Aceptar y continuar
                            </button>
                        </div>

                    </form>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

@endsection

<style>
    .btn-gradient {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }
    .container {
        display: flex !important;
        min-height: 100vh !important;
        max-width: 100% !important;
        margin: 0 auto !important;
        padding: 0 !important;
        justify-content: center !important;
        align-items: center !important;
        background-color: var(--bg-body);
    }

    .card {
        max-width: 90vw;
        margin: 0 auto;
    }
</style>
