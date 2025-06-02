/* Solo Front
const fichajesFiltrados = computed(() => {
    let fichajes = page.props.fichajes || [];

    if (!isAdmin.value) {
        fichajes = fichajes.filter(f => f.usuario_id === user.value?.id);
    }

    if (fechaInicio.value) {
        const inicio = new Date(fechaInicio.value);
        fichajes = fichajes.filter(f => new Date(f.timestamp) >= inicio);
    }
    if (fechaFin.value) {
        const fin = new Date(fechaFin.value);
        fin.setHours(23, 59, 59); // Incluir todo el día
        fichajes = fichajes.filter(f => new Date(f.timestamp) <= fin);
    }

    return fichajes;
});
*/
<script setup>
    import { ref, watch, onMounted } from 'vue';
    import { router } from '@inertiajs/vue3';

    const props = defineProps({
    usuarios: {
    type: Array,
    default: () => [],
},
    isAdmin: {
    type: Boolean,
    default: false,
},
    filters: {
    type: Object,
    default: () => ({}),
}
});

    // Estados con filtros iniciales desde backend
    const usuarioSeleccionado = ref(props.filters.usuarioId || '');
    const fechaInicio = ref(props.filters.fechaInicio || '');
    const fechaFin = ref(props.filters.fechaFin || '');

    const aplicarFiltros = () => {
    router.get(route('registro.fichajes'), {
        usuarioId: usuarioSeleccionado.value,
        fechaInicio: fechaInicio.value,
        fechaFin: fechaFin.value,
        page: 1, // reiniciar paginación
    }, {
        preserveState: true,
        replace: true,
    });
};

    const limpiarFiltros = () => {
    usuarioSeleccionado.value = '';
    fechaInicio.value = '';
    fechaFin.value = '';
    aplicarFiltros();
};

    // Opcional: aplicar automáticamente cuando cambien los inputs
    watch([usuarioSeleccionado, fechaInicio, fechaFin], aplicarFiltros);
</script>

<template>
    <div class="mb-6 bg-white p-3 rounded-lg shadow">
        <!-- Selector de usuario (solo admin) -->
        <div v-if="isAdmin" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 md:mb-0">
            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1 bg-blue-100 px-2 py-1 rounded">
                    Filtrar por Usuario
                </label>
                <select
                    v-model="usuarioSeleccionado"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Todos los usuarios</option>
                    <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">
                        {{ usuario.name }} (ID {{ usuario.id }})
                    </option>
                </select>
            </div>
            <div class="hidden md:block md:col-span-1"></div>
        </div>

        <!-- Filtros de fecha -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 bg-blue-100 px-2 py-1 rounded">
                    Desde
                </label>
                <input
                    type="date"
                    v-model="fechaInicio"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 bg-blue-100 px-2 py-1 rounded">
                    Hasta
                </label>
                <input
                    type="date"
                    v-model="fechaFin"
                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            <div class="col-span-2 md:col-span-1 flex items-end">
                <button
                    @click="limpiarFiltros"
                    class="w-full md:w-auto px-4 py-2 bg-blue-100 text-gray-700 rounded-md hover:bg-gray-300 transition"
                >
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #cbd5e0;
    border-radius: 0.375rem;
    font-size: 1rem;
}

@media (max-width: 768px) {
    input {
        font-size: 0.80rem;
        padding: 0.2rem;
    }
    button {
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
    }
    label {
        font-size: 0.75rem;
    }
}
</style>
