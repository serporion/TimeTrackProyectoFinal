<script setup>
import { ref, computed } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'
import Navbar from "@/Components/Landing/Navbar.vue";
import Footer from "@/Components/Landing/Footer.vue";

const page = usePage()
const usuarios = page.props.usuarios || []
const contratos = page.props.contratos || []
const user = computed(() => page.props.auth?.user)
const isAdmin = computed(() => user.value?.role === 'administrador')

const usuarioSeleccionado = ref('')
const soloVigentes = ref(false)

const contratosFiltrados = computed(() => {
    let filtrados = usuarioSeleccionado.value
        ? contratos.filter(c => c.usuario_id === parseInt(usuarioSeleccionado.value))
        : contratos
    return soloVigentes.value
        ? filtrados.filter(c => !c.fecha_fin)
        : filtrados
})
</script>

<template>
    <Head title="Contratos" />
    <div class="min-h-screen flex flex-col">
        <Navbar />
        <div id="hero-bg-contratos" class="flex-grow hero"> <!-- bg-gradient-to-br from-blue-500 to-cyan-400">-->
            <div class="hero-overlay bg-opacity-10"></div>
            <div class="hero-content flex flex-col items-start px-3 sm:px-6 md:px-12 w-full max-w-2xl mx-auto">
                <div class="flex items-end gap-4 mb-4 mt-8">
                    <a href="/informes" class="btn btn-sm btn-circle bg-white text-blue-600 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <h1 class="text-white text-2xl md:text-3xl font-bold">Contratos</h1>
                </div>

                <div v-if="isAdmin" class="mb-6 w-full max-w-md">
                    <label for="usuario_id" class="text-white font-semibold block mb-2">Filtrar por Usuario</label>
                    <select id="usuario_id" v-model="usuarioSeleccionado"
                            class="p-1 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 mb-2">
                        <option value="">- Todos los usuarios -</option>
                        <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">
                            {{ usuario.name }} (ID {{ usuario.id }})
                        </option>
                    </select>
                </div>

                <div class="w-full overflow-x-auto shadow rounded-lg">
                    <table class="min-w-[300px] w-full bg-white shadow rounded-lg text-sm">
                        <thead class="bg-blue-100 text-gray-700">
                        <tr>
                            <th v-if="isAdmin" class="p-1 text-left">ID</th>
                            <th v-if="isAdmin" class="p-1 text-left">Usuario</th>
                            <th class="p-1 text-right">Horas Semanales</th>
                            <th class="p-1 text-right">Inicio del Contrato</th>
                            <th class="p-1 text-right">Fin del Contrato</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="contrato in contratosFiltrados" :key="contrato.id"
                            class="border-b hover:bg-blue-50 transition-colors">
                            <td v-if="isAdmin" class="p-1">{{ contrato.usuario_id }}</td>
                            <!--
                            <td class="p-1">
                                <a v-if="isAdmin" :href="`/admin/contrato/${contrato.id}/edit`" class="text-blue-600 hover:underline">
                                    {{ contrato.usuario?.name || 'Desconocido' }}
                                </a>
                                <span v-else>
                                    {{ contrato.usuario?.name || 'Desconocido' }}
                                </span>
                            </td>
                            -->
                            <td v-if="isAdmin" class="p-1">
                                <a :href="`/admin/contrato/${contrato.id}/edit`" class="text-blue-600 hover:underline">
                                    {{ contrato.usuario?.name || 'Desconocido' }}
                                </a>
                            </td>
                            <td class="p-1 text-right">{{ contrato.horas }}</td>
                            <td class="p-1 text-right">
                                {{ new Date(contrato.fecha_inicio).toLocaleDateString('es-ES', {
                                day: '2-digit', month: '2-digit', year: 'numeric'
                            }) }}
                            </td>
                            <td class="p-1 text-right">
                                    <span v-if="contrato.fecha_fin" class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ new Date(contrato.fecha_fin).toLocaleDateString('es-ES', {
                                        day: '2-digit', month: '2-digit', year: 'numeric'
                                    }) }}
                                    </span>
                                <span v-else class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Vigente
                                    </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>

<style scoped>

    #hero-bg-contratos {
        background: #5d636b;
    }

    select {
        background-color: white;
        color: #333;
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
        table,select {
            font-size: 0.70rem;
        }
        th, td {
            padding: 0.1rem;
        }
        .hero-content h1 {
            font-size: 1.5rem;
        }
    }
</style>
