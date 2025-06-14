<script setup>

import { onMounted } from 'vue';
import { Carousel } from 'bootstrap';

import cliente1 from '../../../img/cliente1.jpg'
import cliente2 from '../../../img/cliente2.jpg'
import cliente3 from '../../../img/cliente3.jpg'

onMounted(() => {
    new Carousel(document.getElementById('testimonialCarousel'), {
        ride: 'carousel' // Autoplay
    });
});

const opiniones = [
    {
        nombre: 'María Gómez, Gerente de La Trattoria',
        texto: 'TimeTrack ha revolucionado la gestión de nuestro personal. Ahora todo es más eficiente y transparente.',
        img: cliente1
    },
    {
        nombre: 'Juan Pérez, Propietario de Sabores del Mar',
        texto: 'Desde que usamos TimeTrack, nuestros empleados están más satisfechos y la gestión de horarios es mucho más sencilla.',
        img: cliente2
    },
    {
        nombre: 'Ana Martínez, Directora de RRHH en Grupo Gastronómico Fusion',
        texto: 'Una herramienta imprescindible para cualquier restaurante que quiera optimizar sus procesos y cumplir con la legislación.',
        img: cliente3
    }
]
</script>

<template>
    <section id="opiniones" class="pb-0.5">
        <div class="container">
            <h2 class="font-bold text-base sm:text-lg md:text-xl lg:text-2xl mb-10 inline-block relative">Lo que dicen nuestros clientes</h2>
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    <div
                        v-for="(opinion, index) in opiniones"
                        :key="index"
                        :class="['carousel-item', { active: index === 0 }]"
                    >
                        <div class="card h-80">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    <img :src="opinion.img" class="img-fluid rounded-start h-100 object-fit-cover" :alt="opinion.nombre" />
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body">
                                        <p class="card-text">"{{ opinion.texto }}"</p>
                                        <p class="testimonial-author">{{ opinion.nombre }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Opcional: controles del carousel -->
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </div>
    </section>
</template>
