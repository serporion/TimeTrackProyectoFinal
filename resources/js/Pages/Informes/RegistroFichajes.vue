<script setup xmlns="http://www.w3.org/1999/html">
import { ref, computed, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

import Navbar from "@/Components/Landing/Navbar.vue";
import Footer from "@/Components/Landing/Footer.vue";
import FiltrosFichajes from '@/Components/Filtros/FiltrosUsuarioFechas.vue';

import { Head } from '@inertiajs/vue3';

const page = usePage()
const user = computed(() => page.props.auth?.user)
const isAdmin = computed(() => user.value?.role === 'administrador')

const filters = ref({ ...(page.props.filters || {}) });

const mostrarFiltros = ref(false);

const fichajes = computed(() => page.props.fichajes.data || []);

// Filtros
const handleFiltrar = (filtros) => {
    filters.value = filtros;
    router.get(route('registro.fichajes'), {
        ...filtros,
        page: 1,
    }, {
        preserveState: true,
        replace: true,
    });
};


onMounted(() => {
    console.log('🔗 Enlaces de paginación:')
    page.props.fichajes?.links?.forEach((link, i) => {
        console.log(`[${i}] Label: ${link.label}, URL: ${link.url}`)
    })
})


/*
const currentPage = ref(1);
const itemsPerPage = 2;

const totalPages = computed(() =>
    Math.ceil(fichajesFiltrados.value.length / itemsPerPage)
);

const paginatedFichajes = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return fichajesFiltrados.value.slice(start, start + itemsPerPage);
});

const goToPreviousPage = () => {
    if (currentPage.value > 1) currentPage.value--;
};

const goToNextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
};
*/


</script>

<template>
    <Head title="Registro de Fichajes" />
    <div class="min-h-screen flex flex-col">
        <Navbar />
        <div id="hero-bg-registro" class="flex-grow hero">
            <div class="hero-overlay bg-opacity-10"></div>
            <div class="hero-content flex flex-col items-start px-2 py-4 sm:px-6 md:px-12 w-full max-w-2xl mx-auto">
                    <div id="registroFi-informes" class="flex gap-3 mt-8 mb-4">
                        <a href="/informes" class="btn btn-sm ml-1 btn-circle bg-white text-blue-600 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <h1 class="text-white text-2xl md:text-3xl font-bold">Registro de Fichajes</h1>
                    </div>

                    <button
                        @click="mostrarFiltros = !mostrarFiltros"
                        class="ml-1 mb-4 px-4 py-2 bg-blue-500 text-white rounded"
                        style="font-size: 0.85rem;"
                    >
                        {{ mostrarFiltros ? 'Ocultar filtros' : 'Mostrar filtros' }}
                    </button>

                    <FiltrosFichajes
                        v-if="mostrarFiltros"
                        :usuarios="page.props.usuarios"
                        :is-admin="isAdmin"
                        :filters="page.props.filters"
                        class="ml-1"
                    />
                            <!-- Tabla -->
                            <div class="w-full overflow-x-auto p-1 rounded">
                                <table class="min-w-[300px] w-full bg-white shadow rounded text-sm">
                                    <thead class="bg-blue-100 text-gray-700 rounded">
                                    <tr>
                                        <th class="p-2 text-left">ID</th>
                                        <th class="p-2 text-left">Usuario</th>
                                        <th class="p-2 text-left">Tipo</th>
                                        <th class="p-2 text-left">Fecha</th>
                                        <th class="p-2 text-left">Hora</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr
                                        v-for="fichaje in fichajes"
                                        :key="fichaje.id"
                                        class="border-b hover:bg-blue-50 transition-colors"
                                    >
                                        <td class="p-2">{{ fichaje.usuario.id }}</td>
                                        <td class="p-2">{{ fichaje.usuario?.name || 'Desconocido' }}</td>
                                        <td class="p-2">
                                                <span :class="{
                                                    'bg-green-100 text-green-800': fichaje.tipo === 'entrada',
                                                    'bg-red-100 text-red-800': fichaje.tipo === 'salida'
                                                }" class="inline-block px-2 py-1 text-xs font-semibold rounded-full">
                                                    {{ fichaje.tipo }}
                                                </span>
                                        </td>
                                        <td class="p-2">
                                            {{ new Date(fichaje.timestamp).toLocaleDateString('es-ES', {
                                            day: '2-digit',
                                            month: '2-digit',
                                            year: 'numeric'
                                        }) }}
                                        </td>
                                        <td class="p-2">
                                            {{ new Date(fichaje.timestamp).toLocaleTimeString('es-ES', {
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        }) }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="flex gap-2 justify-center mt-4">
                                    <button
                                        v-for="link in page.props.fichajes.links"
                                        :key="link.label"
                                        :disabled="!link.url"
                                        @click="link.url && router.visit(link.url)"
                                        v-html="link.label"
                                        class="px-3 py-1 rounded border"
                                        :class="{
                                          'bg-green-500 text-white': link.active,
                                          'bg-white text-gray-700': !link.active,
                                          'opacity-50 cursor-not-allowed': !link.url
                                        }"
                                    />
                                </div>
                            </div>
                        </div>
                </div>
        <Footer />
    </div>
</template>

<style scoped>

    #hero-bg-registro {
        background: linear-gradient(135deg, #3b82f6 0%, #22d3ee 100%) !important;
    }

    #registroFi-informes {
        align-items: flex-end;
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

    @media (max-width: 768px) {
        table {
            font-size: 0.70rem;
        }
        th, td {
            padding: 0.1rem;
        }
        input, button, label, option, select {
            font-size: 0.70rem;
        }
    }
</style>
