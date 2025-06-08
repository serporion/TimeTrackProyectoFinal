<script setup>

//import '../../css/style.css' //CSSCAMBIO
import Navbar from '@/Components/Landing/Navbar.vue'
import Footer from '@/Components/Landing/Footer.vue'

</script>

<template>
    <div class="landing-layout">
        <Navbar />

        <main>
            <slot />
        </main>

        <Footer />
    </div>
</template>

<style scoped>
.landing-layout {
    min-height: 100dvh;
    display: flex;
    flex-direction: column;
}
main {
    flex: 1;
}
</style>
