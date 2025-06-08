<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import Navbar from "@/Components/Landing/Navbar.vue";
import Footer from "@/Components/Landing/Footer.vue";
import {computed, ref} from "vue";

const page = usePage();
const user = computed(() => page.props.auth?.user);
const isAdmin = computed(() => user.value?.role === 'administrador');
const usuarios = computed(() => page.props.usuarios || []);
const usuarioId = ref(page.props.usuarioId || '');

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
        <div id="hero-bg-horas" class="flex-grow hero"> <!-- bg-gradient-to-br from-blue-500 to-cyan-400">-->
            <div class="hero-overlay bg-opacity-10"></div>
            <div class="hero-content flex flex-col items-start px-3 py-6 sm:px-6 md:px-12 w-full max-w-2xl mx-auto">
                <div id="horas-informes" class="flex gap-3 mt-8 mb-4">
                    <a href="/informes" class="btn btn-sm ml-1 btn-circle bg-white text-blue-600 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <h1 class="text-white text-2xl md:text-3xl font-bold">Horas Trabajadas</h1>
                </div>
                <!-- Select de usuarios -->
                <div v-if="isAdmin" class="col-span-2 sm:col-span-1">
                    <label for="usuario_id" class="block font-semibold text-white mb-1">Empleado</label>
                    <select
                        id="usuario_id"
                        name="usuario_id"
                        v-model="usuarioId"
                        class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400"
                    >
                        <option value="">Todos</option>
                        <option v-for="u in usuarios" :key="u.id" :value="u.id">
                            {{ u.name }} (ID {{ u.id }})
                        </option>
                    </select>
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

                    <input type="hidden" name="usuario_id" :value="usuarioId" />

                    <div class="flex items-end">
                        <button id="btn-enviar" type="submit" class="btn w-full font-semibold py-2 mt-4 rounded transition text-white">
                            Enviar
                        </button>
                    </div>
                </form>

                <!-- Detalle por semana -->
                <div v-if="$page.props.semanas" class="w-full bg-white p-4 rounded shadow mb-6">
                    <h2 class="text-lg font-semibold mb-2">Detalle:</h2>
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

    #hero-bg-horas {
        background: linear-gradient(135deg, #3b82f6 0%, #22d3ee 100%) !important;
    }

    #horas-informes {
        align-items: flex-end;
    }

    #btn-enviar {
        background-color: #2563eb;
        color: #fff;
        font-weight: bold;
        transition: background-color 0.2s;
    }

    #btn-enviar:hover {
        background-color: #1d4ed8;
    }

    .btn-circle {
        border-radius: 50%;
        width: 2.3rem;
        height: 2.3rem;
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
