<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage, router  } from '@inertiajs/vue3'

import logo from '../../../img/logo.jpg'
import DropdownLink from "@/Components/DropdownLink.vue";
const isExpanded = ref(false)
const isDropdownOpen = ref(false)

const page = usePage()
const user = computed(() => page.props.auth.user)
const permisos = computed(() => usePage().props.auth.permisos ?? [])

function toggleNavbar() {
    isExpanded.value = !isExpanded.value
}

function toggleDropdown() {
    isDropdownOpen.value = !isDropdownOpen.value
}

onMounted(() => {
    let lastScrollTop = 0
    const header = document.getElementById('cabecera')
    const isMobile = window.innerWidth <= 768

    if (header && !isMobile) {
        const headerHeight = header.offsetHeight
        const body = document.body

        window.addEventListener('scroll', function () {
            const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop
            if (currentScrollTop > lastScrollTop && currentScrollTop > headerHeight) {
                header.style.top = `-${headerHeight + 6}px`
            } else {
                header.style.top = '0'
                body.style.paddingTop = `${headerHeight}px`
            }
            lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop
        }, false)
    }

    document.addEventListener('click', (e) => {
        const dropdown = document.getElementById('customDropdown')
        if (dropdown && !dropdown.contains(e.target)) {
            isDropdownOpen.value = false
        }
    })

})

const cerrarSesion = () => {
    // 1. Cierra el dropdown visualmente
    isDropdownOpen.value = false;

    // 2. Logout (con pequeño delay para feedback visual)
    setTimeout(() => {
        router.post(route('logout'));
    }, 150);
};

</script>

<template>
    <header id="cabecera" class="fixed-top header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container pr-1">
                <!-- Logo y nombre -->
                <a class="navbar-brand d-flex align-items-center gap-2" :href="route('landing')">
                    <img :src="logo" alt="TimeTrack Logo" class="d-inline-block navbar-logo" />

                    <!-- Muestro "TimeTrack" si "no" hay nombre de usuario en vista móvil o siempre en escritorio -->
                    <span id="textLogo"
                        class="text-white fw-bold"
                        :class="{
                          'd-none d-lg-inline': user,
                          'd-inline': !user
                    }"
                                    >
                        TimeTrack
                     </span>
                </a>

                <template v-if="!user">
                    <a
                        :href="route('login')"
                        class="text-white fw-bold me-2 d-lg-none"
                        style="font-size: 0.9rem;"
                    >
                        Login
                    </a>
                </template>

                <!-- Botón hamburguesa -->
                <button
                    class="navbar-toggler collapsed"
                    type="button"
                    @click="toggleNavbar"
                    :aria-expanded="isExpanded.toString()"
                    aria-controls="navbarNav"
                >
                    <!-- Nombre de usuario -->
                    <span  v-if="user"
                           class="text-white fw-semibold d-lg-none mr-2"
                           style="max-width: 100px; font-size: 0.8rem;">
                    {{ user?.name }}
                    </span>
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menú colapsable -->
                <div class="collapse navbar-collapse" :class="{ show: isExpanded }" id="navbarNav">
                    <ul class="navbar-nav w-100">
                        <template v-if="user">
                            <template v-if="user.role === 'administrador'">
                                <!-- enlaces para administrador -->
                                <li class="nav-item" v-if="permisos.includes('gestionar_usuarios')"><a class="nav-link pe-1" href="/admin/usuario">Gestión de usuarios</a></li>
                                <li class="nav-item" v-if="permisos.includes('gestionar_fichajes')"><a class="nav-link pe-1" href="/informes">Informes</a></li>
                                <li class="nav-item" v-if="permisos.includes('gestionar_permisos')"><a class="nav-link pe-1" href="/admin/administrador">Gestión de permisos</a></li>
                                <li class="nav-item" v-if="permisos.includes('gestionar_usuarios')">
                                    <a class="nav-link pe-1" :href="route('profile.edit')">Configuración</a>
                                </li>
                            </template>
                            <template v-else-if="user.role === 'empleado'">
                                <!-- enlaces para empleado -->
                                <li class="nav-item"><a class="nav-link pe-1" href="/fichar">Fichar</a></li>
                                <li class="nav-item"><a class="nav-link pe-1" href="/informes">Informes</a></li>
                                <li class="nav-item">
                                    <a class="nav-link pe-1" :href="route('profile.edit')">Configuración</a>
                                </li>
                            </template>
                            <li class="nav-item dropdown" id="customDropdown" v-if="!isExpanded">
                                <a
                                    class="nav-link dropdown-toggle text-white fw-semibold"
                                    href="#"
                                    @click.prevent="toggleDropdown"
                                >
                                    {{ user?.name }}
                                </a>

                                <ul
                                    class="dropdown-menu dropdown-menu-end show botonCerrar text-end"
                                    :style="{ display: isDropdownOpen ? 'block' : 'none', minWidth: '5rem' }"
                                    style="position: absolute; right: 0; top: 100%;"
                                >
                                    <li>
                                        <a class="dropdown-item pe-3 font-bold" href="#" @click.prevent="cerrarSesion">
                                            Cerrar sesión
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li v-if="isExpanded" class="nav-item d-lg-none">
                                <a class="nav-link font-bold pe-1 text-danger" href="#" @click.prevent="cerrarSesion">
                                    Cerrar sesión
                                </a>
                            </li>
                        </template>
                        <!-- Si no está autenticado -->
                        <template v-else>
                            <li class="nav-item"><a class="nav-link" :href="route('landing')">Inicio</a></li>
                            <li class="nav-item"><a class="nav-link" :href="route('caracteristicas')">Características</a></li>
                            <li class="nav-item"><a class="nav-link" href="#opiniones">Opiniones</a></li>
                            <li class="nav-item"><a class="nav-link" :href="route('contacto')">Contacto</a></li>
                            <li class="nav-item"><a class="nav-link d-none d-lg-block" :href="route('login')">Login</a></li>
                        </template>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</template>


<style scoped>

    .botonCerrar{
        background-color: var(--color-heading) !important;
    }

    .dropdown-item.font-bold{
        color: var(--color-link) !important;
        background-color: var(--color-heading) !important;
    }

</style>
