<script setup>
import { Head, usePage, router, Link  } from '@inertiajs/vue3'
import Navbar from '@/Components/Landing/Navbar.vue'
import Footer from '@/Components/Landing/Footer.vue'

import { computed } from 'vue'
//const user = usePage().props.auth.user
const user = computed(() => usePage().props.auth.user) //Reactivo pos si cambia el usuario.
const permisos = computed(() => usePage().props.auth.permisos ?? [])

function goTo(destino) {
    switch (destino) {
        case 'fichar':
            router.visit('/fichar')
            break
        case 'informes':
            router.visit('/informes')
            break
        case 'configuser':
            router.visit(route('profile.edit'))
            break
        case 'usuarios':
            router.visit('/admin/usuario')
            break
        case 'config':
            router.visit('/configuracion')
            break
        case 'permisos':
            router.visit('/admin/administrador')
            break
        case 'iniciar':
            router.visit('/lector')
            break
    }
}

function irAGestionPermisos() {

    if (typeof window !== 'undefined') {
        window.location.href = '/admin/administrador';
    }
}


</script>

<template>
    <div class="min-h-screen flex flex-col">
        <Head title="Dashboard" />
        <Navbar />

        <div class="hero flex-grow">
            <section class="text-center">
                <div class="hero-overlay"></div>
                <div class="hero-content d-flex flex-column justify-content-center align-items-center px-3 mb-4 mb-sm-0">
                    <h1 class="text-white text-4xl text-center font-semibold">
                        ¡Bienvenido/a al Sistema!
                    </h1>
                    <p class="text-white text-xl text-center md:mb-7 lg:mb-10">Accede a tus informes, ficha tu entrada/salida y más</p>

                    <div class="d-grid gap-3 py-4" style="margin: 0 auto;">
                        <!-- enlaces para empleado -->
                        <template v-if="user && user.role === 'empleado'">
                            <Link
                                href="/fichar"
                                class="btn btn-lg px-6 py-2.5 rounded-pill border-0 fw-bold"
                                style="background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);"
                            >
                                Fichar
                            </Link>

                            <button
                                class="btn btn-lg px-6 py-2.5 rounded-pill border-0 fw-bold"
                                style="background: linear-gradient(135deg, #3a7bd5, #00d2ff); color: white; box-shadow: 0 4px 15px rgba(58, 123, 213, 0.4);"
                                @click="goTo('informes')"
                            >
                                Informes
                            </button>
                            <button
                                class="btn btn-lg px-6 py-2.5 rounded-pill border-0 fw-bold"
                                style="background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);"
                                @click="goTo('configuser')"
                            >
                                Configuración
                            </button>
                        </template>
                        <!-- enlaces para administrador -->
                        <template v-else-if="user && user.role === 'administrador'">
                            <button v-if="permisos.includes('gestionar_usuarios')"
                                class="btn btn-lg px-6 py-2.5 rounded-pill border-0 fw-bold"
                                style="background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);"
                                @click="goTo('usuarios')"
                            >
                                Gestión de Usuarios
                            </button>
                            <button v-if="permisos.includes('gestionar_fichajes')"
                                class="btn btn-lg px-6 py-2.5 rounded-pill border-0 fw-bold"
                                style="background: linear-gradient(135deg, #3a7bd5, #00d2ff); color: white; box-shadow: 0 4px 15px rgba(58, 123, 213, 0.4);"
                                @click="goTo('informes')"
                            >
                                Generación Informes
                            </button>
                            <button v-if="permisos.includes('gestionar_permisos')"
                                    class="btn btn-lg px-6 py-2.5 rounded-pill border-0 fw-bold"
                                    style="background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);"
                                    @click="irAGestionPermisos"
                            >
                                Gestión de permisos
                            </button>
                            <button v-if="permisos.includes('gestionar_usuarios')"
                                class="btn btn-lg px-6 py-2.5 rounded-pill border-0 fw-bold"
                                style="background: linear-gradient(135deg, #3a7bd5, #00d2ff); color: white; box-shadow: 0 4px 15px rgba(58, 123, 213, 0.4);"
                                @click="goTo('config')"
                            >
                                Configuración
                            </button>
                            <button v-if="permisos.includes('gestionar_inicio')"
                                    class="btn btn-lg px-6 py-2.5 rounded-pill border-0 fw-bold"
                                    style="background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);"
                                    @click="goTo('iniciar')"
                            >
                                Inicio de Aplicación Fichaje
                            </button>

                        </template>
                        <template v-else>
                            <p>NINGUN USUARIO</p>
                        </template>
                    </div>
                </div>
            </section>
        </div>
        <Footer />
    </div>
</template>
