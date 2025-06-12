<script setup>
import { Head } from '@inertiajs/vue3'
import Navbar from '@/Components/Landing/Navbar.vue'
import Footer from '@/Components/Landing/Footer.vue'
</script>

<template>
    <Head title="Características de TimeTrack" />
    <div class="min-h-screen flex flex-col">
        <Navbar />

        <section id="hero-caracteristicas" class="flex-grow hero bg-gray-50 py-6 px-6">
            <div class="max-w-4xl mx-auto text-center hero-content">
                <h1 class="text-3xl sm:text-4xl font-bold mb-4">
                    Cumplimiento y Gestión
                </h1>
                <p class="text-lg text-gray-700 mb-10">
                    Nuestra solución combina tecnología QR, fotografía en fichajes, y una gestión de horarios intuitiva para ayudarte a cumplir la ley y optimizar tu empresa.
                </p>

                <div class="grid md:grid-cols-3 gap-6 text-left">
                    <div class="bg-white p-6 rounded shadow">
                        <h2 class="text-xl font-semibold text-blue-600 mb-2">✔️ Fichaje fácil y seguro</h2>
                        <p class="text-gray-700">
                            Los empleados fichan escaneando un QR desde un terminal autorizado. El sistema capta su imagen para garantizar la autenticidad y prevenir fraudes.
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded shadow">
                        <h2 class="text-xl font-semibold text-blue-600 mb-2">📊 Informes mensuales</h2>
                        <p class="text-gray-700">
                            Genera resúmenes automáticos de horas trabajadas, diferencias con el horario pactado y alertas en caso de irregularidades.
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded shadow">
                        <h2 class="text-xl font-semibold text-blue-600 mb-2">🔒 Cumplimiento legal</h2>
                        <p class="text-gray-700">
                            Adaptado a la normativa española vigente y preparado para la nueva ley de 2026 sobre control horario digital obligatorio.
                        </p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mt-10 text-left">
                    <div class="bg-white p-6 rounded shadow">
                        <h2 class="text-xl font-semibold text-blue-600 mb-2">⚙️ Automatización y control</h2>
                        <p class="text-gray-700">
                            Detecta si el fichaje es de entrada o salida automáticamente. Gestiona contratos, jornadas semanales y seguimiento del personal en tiempo real.
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded shadow">
                        <h2 class="text-xl font-semibold text-blue-600 mb-2">🌐 Adaptable a cualquier dispositivo</h2>
                        <p class="text-gray-700">
                            Funciona perfectamente en móviles, tablets y ordenadores. Ideal para entornos como restaurantes, bares, hoteles y pymes con necesidades reales.
                        </p>
                    </div>
                </div>

                <div class="mt-3">
                    <a :href="route('contacto')" class="btn bg-blue-600 hover:bg-blue-700 text-white text-lg px-6 py-3 rounded-lg shadow">
                        Quiero una demo
                    </a>
                </div>
            </div>
        </section>

        <Footer />
    </div>
</template>

<style scoped>
.hero {
    background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%) !important;
}

#hero-caracteristicas{
    background: linear-gradient(135deg, #3b82f6 0%, #22d3ee 100%) !important;
}
</style>

