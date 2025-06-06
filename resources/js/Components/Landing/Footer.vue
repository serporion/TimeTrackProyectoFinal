<script setup>

</script>

<template>
    <footer class="bg-gray-900 text-white py-3">
        <div class="text-center">
            <div class="flex gap-4 justify-center pb-2">
                <a href="#" class="text-white hover:text-blue-400">
                    <i class="bi bi-facebook text-xl md:text-2xl"></i>
                </a>
                <a href="#" class="text-white hover:text-sky-400">
                    <i class="bi bi-twitter text-xl md:text-2xl"></i>
                </a>
                <a href="#" class="text-white hover:text-blue-600">
                    <i class="bi bi-linkedin text-xl md:text-2xl"></i>
                </a>
                <a :href="route('contacto')" class="text-white hover:text-yellow-400">
                    <i class="bi bi-envelope text-xl md:text-2xl"></i>
                </a>
            </div>

            <p class="text-gray-400 text-sm mb-1">
                © 2025 TimeTrack. Todos los derechos reservados.
            </p>

            <a href="#" class="text-white text-sm underline hover:text-gray-300">
                Política de Privacidad
            </a>
        </div>
    </footer>
</template>

<style scoped>

</style>
