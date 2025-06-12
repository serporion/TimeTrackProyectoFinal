<script setup>


</script>

<template>
    <section id="hero" class="hero vh-50 vh-md-100 pb-3 d-flex align-items-center position-relative">
        <div class="hero-overlay"></div>
        <div class="container text-center text-white position-relative z-1">
            <h1 class="display-4 fw-bold pt-5">TimeTrack: Fichaje Inteligente para Restaurantes</h1>
            <p class="lead">Simplifica el control de horarios y cumple con la ley sin complicaciones</p>
            <a :href="route('contacto')" class="btn btn-primary btn-lg">Pida una prueba</a>
        </div>
    </section>
</template>

<style scoped>

@media (min-width: 768px) {
    p, a {
        margin-top: 2rem !important;
    }
}
</style>

