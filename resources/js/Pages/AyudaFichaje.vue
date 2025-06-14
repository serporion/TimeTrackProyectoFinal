<script setup>
import { Head } from '@inertiajs/vue3'
import Navbar from '@/Components/Landing/Navbar.vue'
import Footer from '@/Components/Landing/Footer.vue'
</script>

<template>
    <Head title="Ayuda para Fichar" />
    <div class="min-h-screen flex flex-col">
        <Navbar />

        <main id="hero-bg-ayuda" class="flex-grow hero px-4 max-w-3xl mx-auto">
            <div class="bg-white shadow-lg rounded-xl p-6 md:p-10">
                <h1 class="text-2xl font-bold mb-6 text-center !text-[#4a5568]">🕒 ¿Cómo funciona el sistema de fichaje?</h1>

                <div class="space-y-6 text-gray-800 text-base md:text-lg leading-relaxed">
                    <p>
                        Para que un fichaje sea válido, debes seguir estos pasos:
                    </p>

                    <ol class="list-decimal list-inside space-y-4">
                        <li>
                            Desde el panel principal, se generará un código QR único que representa tu intención de fichar.
                        </li>
                        <li>
                            Ese QR debe ser escaneado desde un dispositivo autorizado que tenga acceso a cámara. Tienes unos segundos para que no caduque dicho QR.
                        </li>
                        <li>
                            Tras el escaneo, el sistema te pedirá realizar una foto rápida para verificar que eres tú el que estás fichando.
                        </li>
                        <li>
                            Una vez validado el QR y tomada la foto, el fichaje se registra como <strong>correcto</strong> y será guardado.
                        </li>
                        <li>
                            Puede que te salten <strong>advertencias</strong> tras el fichaje. Leelas atentamente y contacta si lo crees conveniente con RRHH.
                        </li>
                    </ol>

                    <hr class="my-6 border-t border-gray-600">

                    <p>
                        ⏱️ Importante: el código QR expira a los 15 segundos si no se escanea a tiempo. Si eso ocurre, tendrás que generar uno nuevo.
                    </p>

                    <p>
                        📷 La foto es necesaria para que el fichaje se considere válido. Si omites este paso, el registro no se considerará válido.
                    </p>

                    <p>
                        Si tienes cualquier problema técnico, puedes contactarnos escribiéndonos incluyendo tú teléfono, será más rápido atenderte.
                    </p>
                </div>

                <div class="text-center mt-8">
                    <button
                        class="btn btn-demo md:w-auto text-white font-semibold rounded hover:bg-blue-700"
                        @click="$inertia.visit('/dashboard')"
                    >
                        Volver al panel
                    </button>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>

<style scoped>

    ol {
        font-family: var(--font-secondary);
    }

    #hero-bg-ayuda {
        background: #FFFFFF;
        padding-top: 8rem !important;
        padding-bottom: 3rem;
    }

    .btn-demo {
        border-radius: 30px;
        padding: 0.6rem 1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        background: linear-gradient(to right, #4a5568, #5d636b);
        border: none;
        box-shadow: 0 4px 15px rgba(58, 123, 213, 0.4);
        color: #fff;
    }

</style>
