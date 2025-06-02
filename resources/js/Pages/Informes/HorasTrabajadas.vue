<script setup>
import { Head } from "@inertiajs/vue3";
import Navbar from "@/Components/Landing/Navbar.vue";
import Footer from "@/Components/Landing/Footer.vue";

const currentYear = new Date().getFullYear();
const anios = Array.from({ length: 5 }, (_, i) => currentYear - i);

const meses = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

</script>

<template>
    <Head title="Horas Trabajadas" />
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Navbar />
        <div class="flex-grow hero bg-gradient-to-br from-blue-500 to-cyan-400">
            <div class="hero-overlay bg-opacity-10"></div>
            <div class="hero-content flex flex-col items-start px-3 py-6 sm:px-6 md:px-12 w-full max-w-2xl mx-auto">
                <div class="flex items-end gap-3 mb-4 mt-8 w-full">
                    <a href="/informes" class="btn btn-sm btn-circle bg-white text-blue-600 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <h1 class="text-white text-2xl md:text-3xl font-bold">Horas Trabajadas</h1>
                </div>

                <!-- Formulario -->
                <form method="GET" action="/informes/horas" class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 w-full mb-6">
                    <div>
                        <label for="mes" class="block font-semibold text-white mb-1">Mes</label>
                        <select name="mes" id="mes" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                            <option v-for="(nombre, index) in meses" :key="index" :value="index + 1">
                                {{ nombre }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="anio" class="block font-semibold text-white mb-1">Año</label>
                        <select name="anio" id="anio" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                            <option v-for="a in anios" :key="a" :value="a">{{ a }}</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn w-full bg-blue-600 text-white font-semibold py-2 mt-4 rounded hover:bg-blue-700 transition">
                            Enviar
                        </button>
                    </div>
                </form>

                <!-- Detalle por semana -->
                <div v-if="$page.props.semanas" class="w-full bg-white p-4 rounded shadow mb-6">
                    <h2 class="text-lg font-semibold mb-2">Detalle semanal:</h2>
                    <ul>
                        <li v-for="(info, semana) in $page.props.semanas" :key="semana" class="mb-1">
                            Semana que comienza el <strong>{{ semana }}</strong>:
                            <span class="text-blue-600">{{ info.horas }}h ({{ info.minutos_legibles }})</span>
                        </li>
                    </ul>
                </div>

                <!-- Resumen mensual -->
                <div v-if="$page.props.resumen" class="w-full bg-white p-4 rounded shadow">
                    <h2 class="text-lg font-semibold mb-2">Resumen del mes</h2>
                    <p>Total trabajadas: <strong>{{ $page.props.resumen.trabajadas }}h ({{ $page.props.resumen.trabajadas_legibles }})</strong></p>
                    <p>Total esperadas: <strong>{{ $page.props.resumen.esperadas }}h</strong></p>
                    <p>
                        Diferencia:
                        <strong :class="{
                                'text-green-600': $page.props.resumen.diferencia >= 0,
                                'text-red-600': $page.props.resumen.diferencia < 0
                            }">
                            {{ $page.props.resumen.diferencia }}h ({{ $page.props.resumen.diferencia_legibles }})
                        </strong>
                    </p>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>

<style scoped>

    .btn-circle {
        border-radius: 50%;
        width: 2.2rem;
        height: 2.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    @media (max-width: 640px) {
        .hero-content {
            padding: 1.5rem 0.5rem;
        }
        input, button, label, option, select {
            font-size: 0.95rem;
        }

    }
</style>
