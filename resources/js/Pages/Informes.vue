<script setup>
import {computed, ref} from 'vue'
import Navbar from "@/Components/Landing/Navbar.vue";
import Footer from "@/Components/Landing/Footer.vue";
import {Head, usePage} from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3';

const current = ref('')

const page = usePage()
const user = computed(() => page.props.auth?.user)
const permisos = computed(() => page.props.auth?.permisos || [])

const isAdmin = computed(() =>
    user.value?.role === 'administrador' && permisos.value.includes('gestionar_fichajes')
)

const navigate = (route) => {
    router.visit(route);
}
</script>

<template>
    <Head title="Informes"/>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Navbar />
        <div class="flex-grow hero">
            <div class="hero-overlay bg-opacity-10"></div>
            <div class="hero-content flex flex-col items-center px-4 mb-sm-0">
                <h1 class="text-4xl text-white drop-shadow-[0_0_10px_rgba(255,255,255,0.8) font-bold mb-10 text-center drop-shadow-md">Informes</h1>

                <div class="grid gap-4 max-w-md">
                    <button
                        @click="navigate('/informes/horas')"
                        class="btn btn-lg py-2.5 rounded-pill border-0 fw-bold text-white"
                        style="background: linear-gradient(135deg, #00c6ff, #0072ff); box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);"
                    >
                        Horas Trabajadas
                    </button>
                    <button
                        @click="navigate('/informes/fichajes')"
                        class="btn btn-lg rounded-pill border-0 fw-bold text-white"
                        style="background: linear-gradient(135deg, #3a7bd5, #00d2ff); box-shadow: 0 4px 15px rgba(58, 123, 213, 0.4);"
                    >
                        Registro de Fichajes
                    </button>
                    <button
                        @click="navigate('/informes/contratos')"
                        class="btn btn-lg py-2.5 rounded-pill border-0 fw-bold text-white"
                        style="background: linear-gradient(135deg, #00c6ff, #0072ff); box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);"
                    >
                        Contratos
                    </button>
                    <button
                        v-if="isAdmin"
                        @click="navigate('/informes/exportar')"
                        class="btn btn-lg py-2.5 rounded-pill border-0 fw-bold text-white"
                        style="background: linear-gradient(135deg, #3a7bd5, #00d2ff); box-shadow: 0 4px 15px rgba(58, 123, 213, 0.4);"
                    >
                        Ficheros
                    </button>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>

<style scoped>
    .estilo-blanco {
        color: #F8F8F8 !important;
    }

    .hero {
        position: relative;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {

        .hero-content {
            padding: 2rem 1rem;
        }
    }
</style>
