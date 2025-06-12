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
            <h1 class="text-2xl font-bold mb-6 text-center">🕒 ¿Cómo funciona el sistema de fichaje?</h1>

            <div class="space-y-5 text-gray-800 text-lg leading-relaxed">
                <p>
                    Para que un fichaje sea válido, debes seguir estos pasos:
                </p>

                <ol class="list-decimal list-inside space-y-3">
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

                <p>
                    ⏱️ Importante: el código QR expira a los 15 segundos si no se escanea a tiempo. Si eso ocurre, tendrás que generar uno nuevo.
                </p>

                <p>
                    📷 La foto es necesaria para que el fichaje se considere válido. Si omites este paso, el registro no se considerará válido.
                </p>

                <p>
                    Si tienes cualquier problema técnico, puedes contactarnos escribiéndonos un correo incluyendo tú teléfono.
                </p>
            </div>

            <div class="text-center mt-8">
                <button
                    class="btn btn-success"
                    @click="$inertia.visit('/dashboard')"
                >
                    Volver al panel
                </button>
            </div>
        </main>

        <Footer />
    </div>
</template>

<style scoped>

    #hero-bg-ayuda {
        background: linear-gradient(135deg, #3b82f6 0%, #22d3ee 100%) !important;
        padding-top: 8rem !important;
        padding-bottom: 3rem;
    }

</style>
