<script setup>

</script>

<template>
    <footer class="bg-dark text-white d-flex flex-row align-items-center justify-content-center py-2">
        <ul>
            <li class="nav-item w-100 text-center">
                <div class="d-flex justify-content-center gap-4">
                    <a href="#"><i class="bi bi-facebook text-white"></i></a>
                    <a href="#"><i class="bi bi-twitter text-white"></i></a>
                    <a href="#"><i class="bi bi-linkedin text-white"></i></a>
                    <a :href="route('contacto')"><i class="bi bi-envelope text-white"></i></a>
                </div>
                <p class="my-2 text-white-50 small">© 2025 TimeTrack. Todos los derechos reservados.</p>
                <a href="#" class="text-white small mb-5">Política de Privacidad</a>
            </li>
        </ul>
    </footer>
</template>

<style scoped>

</style>
