<script setup>
import Navbar from '@/Components/Landing/Navbar.vue'
import Footer from '@/Components/Landing/Footer.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <Navbar />

    <div class="flex-grow flex flex-col items-center justify-center text-center p-6">
      <h1 class="text-6xl font-bold text-blue-700 mb-4">404</h1>
      <p class="text-xl text-gray-700 mb-6">La página que buscas no existe.</p>
      <Link href="/" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
        Volver al inicio
      </Link>
    </div>

    <Footer />
  </div>
</template>

<style scoped>

</style>
