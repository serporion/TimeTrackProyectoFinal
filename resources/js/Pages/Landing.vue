<script setup>
defineOptions({ layout: LandingLayout })

import LandingLayout from '@/Layouts/LandingLayout.vue'
import Hero from '@/Components/Landing/Hero.vue'
import Caracteristicas from '@/Components/Landing/Caracteristicas.vue'
import Opiniones from '@/Components/Landing/Opiniones.vue'
import Modal from '@/Components/Landing/Modal.vue'
</script>

<template>
    <Hero />
    <Caracteristicas />
    <Opiniones />
    <Modal />
</template>


<style scoped>

</style>
