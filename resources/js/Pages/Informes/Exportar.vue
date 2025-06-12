<script setup>
import { Head, router } from '@inertiajs/vue3'
import Navbar from '@/Components/Landing/Navbar.vue'
import Footer from '@/Components/Landing/Footer.vue'
import { ref } from 'vue'

defineProps({ archivos: Array })

const currentYear = new Date().getFullYear()
const currentMonth = new Date().getMonth() + 1

const meses = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
]

const anio = ref(currentYear)
const mes = ref(currentMonth)

const mostrarArchivos = ref(false)

const generar = () => {
    router.post('/informes/exportar', { anio: anio.value, mes: mes.value })
}
</script>

<template>
    <Head title="Exportar Horarios" />
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Navbar />
        <div id="hero-bg-exportar" class="flex-grow hero">
            <div class="hero-overlay bg-opacity-10"></div>
            <div class="hero-content flex flex-col items-start px-3 py-6 sm:px-6 md:px-12 w-full max-w-2xl mx-auto">
                <div class="flex items-end gap-3 mb-4 mt-8 w-full">
                    <a href="/informes" class="btn btn-sm btn-circle bg-white text-blue-600 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <h1 class="text-white text-2xl md:text-3xl font-bold">Generar Registros</h1>
                </div>

                <!-- Formulario de mes y año -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4 w-full">
                    <div>
                        <label class="text-white block mb-1 text-sm font-medium">Mes</label>
                        <select v-model="mes" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                            <option v-for="(nombre, index) in meses" :key="index" :value="index + 1">
                                {{ nombre }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="text-white block mb-1 text-sm font-medium">Año</label>
                        <select v-model="anio" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                            <option v-for="a in [currentYear, currentYear - 1, currentYear - 2]" :value="a" :key="a">
                                {{ a }}
                            </option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button
                            @click="generar"
                            class="w-full bg-blue-600 text-white rounded px-3 py-2 text-sm font-semibold hover:bg-blue-700 transition"
                        >
                            Generar
                        </button>
                    </div>
                </div>

                <button
                    @click="mostrarArchivos = !mostrarArchivos"
                    class="w-full sm:w-auto px-2 py-2 mb-4 text-sm font-semibold text-white bg-blue-600 rounded shadow hover:bg-blue-700 transition duration-200"
                >
                    {{ mostrarArchivos ? 'Ocultar archivos disponibles' : 'Mostrar archivos disponibles' }}
                </button>

                <div v-if="mostrarArchivos">
                    <ul class="list-disc ml-5 space-y-1">
                        <li v-for="archivo in archivos" :key="archivo">
                            <a :href="`/informes/exportar/${archivo}`" class="text-white hover:underline">
                                {{ archivo }}
                            </a>
                        </li>
                    </ul>
                </div>


                <div id="successFichero" v-if="$page.props.flash?.success" class="bg-green-100 text-green-800 px-4 py-2 rounded">
                    {{ $page.props.flash.success }}
                </div>

            </div>
        </div>
        <Footer />
    </div>
</template>

<style scoped>

    #hero-bg-exportar {
        background: linear-gradient(135deg, #3b82f6 0%, #22d3ee 100%) !important;
    }

    #successFichero {
        margin-top: 0.5rem;
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
